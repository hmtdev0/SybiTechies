<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use App\Models\Technology;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function index(Request $request): View
    {
        $this->authorize('view portfolio');

        $projects = Project::query()
            ->with('category')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('category'), fn ($q) => $q->where('project_category_id', $request->integer('category')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status') === 'active'))
            ->orderBy('display_order')
            ->paginate(12)
            ->withQueryString();

        return view('admin.projects.index', [
            'projects' => $projects,
            'categories' => ProjectCategory::query()->orderBy('name')->get(),
            'breadcrumb' => 'Projects',
        ]);
    }

    public function create(): View
    {
        $this->authorize('create portfolio');

        return view('admin.projects.create', [
            'categories' => ProjectCategory::query()->active()->ordered()->get(),
            'technologies' => Technology::query()->active()->ordered()->get(),
            'breadcrumb' => 'Projects — Add New',
        ]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $this->authorize('create portfolio');

        $data = $request->safe()->except([
            'technologies', 'features', 'images', 'thumbnail',
            'seo_title', 'seo_meta_description', 'seo_meta_keywords', 'seo_canonical_url',
            'seo_og_image', 'seo_og_title', 'seo_og_description', 'seo_schema_json', 'seo_robots',
        ]);
        // Editors can create projects but not publish them — force unpublished
        // regardless of what the form submitted unless they're allowed to publish.
        $data['status'] = $request->user()->can('publish portfolio') && $request->boolean('status', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data = $this->sanitizeRichTextFields($data);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->uploads->store($request->file('thumbnail'), 'projects');
        }

        $project = Project::create($data);

        $this->syncTechnologies($project, $request);
        $this->syncFeatures($project, $request);
        $this->appendGalleryImages($project, $request);
        $this->syncSeo($project, $request);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project): View
    {
        $this->authorize('edit portfolio');

        $project->load(['technologies', 'features', 'images', 'seo']);

        return view('admin.projects.edit', [
            'project' => $project,
            'categories' => ProjectCategory::query()->active()->ordered()->get(),
            'technologies' => Technology::query()->active()->ordered()->get(),
            'breadcrumb' => 'Projects — Edit',
        ]);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('edit portfolio');

        $data = $request->safe()->except([
            'technologies', 'features', 'images', 'thumbnail',
            'seo_title', 'seo_meta_description', 'seo_meta_keywords', 'seo_canonical_url',
            'seo_og_image', 'seo_og_title', 'seo_og_description', 'seo_schema_json', 'seo_robots',
        ]);
        // Without publish rights, editing never changes publish status either
        // way — it stays whatever it already was, so a content fix can't
        // accidentally publish (or unpublish) live content.
        $data['status'] = $request->user()->can('publish portfolio')
            ? $request->boolean('status', true)
            : $project->status;
        $data['is_featured'] = $request->boolean('is_featured');
        $data = $this->sanitizeRichTextFields($data);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->uploads->replace($project->thumbnail, $request->file('thumbnail'), 'projects');
        }

        $project->update($data);

        $this->syncTechnologies($project, $request);
        $this->syncFeatures($project, $request);
        $this->appendGalleryImages($project, $request);
        $this->syncSeo($project, $request);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete portfolio');

        $this->uploads->delete($project->thumbnail);
        $project->images->each(fn (ProjectImage $image) => $this->uploads->delete($image->image_path));
        if ($project->seo) {
            $this->uploads->delete($project->seo->og_image);
            $project->seo->delete();
        }

        $project->delete();

        return back()->with('success', 'Project deleted.');
    }

    public function toggleStatus(Project $project): RedirectResponse
    {
        $this->authorize('publish portfolio');

        $project->update(['status' => ! $project->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $this->authorize('delete portfolio');

        $ids = (array) $request->input('ids', []);
        $projects = Project::query()->whereIn('id', $ids)->with('images', 'seo')->get();

        foreach ($projects as $project) {
            $this->uploads->delete($project->thumbnail);
            $project->images->each(fn (ProjectImage $image) => $this->uploads->delete($image->image_path));
            $project->seo?->delete();
        }

        Project::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' projects deleted.');
    }

    public function destroyImage(Project $project, ProjectImage $projectImage): RedirectResponse
    {
        $this->authorize('edit portfolio');

        abort_unless($projectImage->project_id === $project->id, 404);

        $this->uploads->delete($projectImage->image_path);
        $projectImage->delete();

        return back()->with('success', 'Gallery image removed.');
    }

    /**
     * The description/challenges/solutions fields are edited with a rich-text
     * (Quill) editor and stored/rendered as raw HTML. Only an authenticated
     * admin can reach this endpoint, but as a defense-in-depth measure we
     * still strip anything outside Quill's own output vocabulary (no
     * <script>/<iframe>/event-handler attributes/javascript: URLs can slip
     * through a pasted snippet).
     */
    protected function sanitizeRichTextFields(array $data): array
    {
        $allowedTags = '<p><h2><h3><strong><em><u><s><ol><ul><li><blockquote><a><img><br>';

        foreach (['description', 'challenges', 'solutions'] as $field) {
            if (empty($data[$field])) {
                continue;
            }

            $html = strip_tags($data[$field], $allowedTags);
            $html = preg_replace('/\s(on\w+)\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $html);
            $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '$1=$2#$2', $html);

            $data[$field] = $html;
        }

        return $data;
    }

    protected function syncTechnologies(Project $project, Request $request): void
    {
        $project->technologies()->sync($request->input('technologies', []));
    }

    protected function syncFeatures(Project $project, Request $request): void
    {
        $project->features()->delete();

        $features = collect($request->input('features', []))
            ->map(fn ($text) => trim((string) $text))
            ->filter()
            ->values();

        foreach ($features as $i => $text) {
            $project->features()->create(['feature_text' => $text, 'display_order' => $i + 1]);
        }
    }

    protected function appendGalleryImages(Project $project, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $nextOrder = (int) $project->images()->max('display_order') + 1;

        foreach ($request->file('images') as $i => $file) {
            $path = $this->uploads->store($file, 'projects');
            $project->images()->create(['image_path' => $path, 'display_order' => $nextOrder + $i]);
        }
    }

    protected function syncSeo(Project $project, Request $request): void
    {
        $seoData = array_filter([
            'title' => $request->input('seo_title'),
            'meta_description' => $request->input('seo_meta_description'),
            'meta_keywords' => $request->input('seo_meta_keywords'),
            'canonical_url' => $request->input('seo_canonical_url'),
            'og_title' => $request->input('seo_og_title'),
            'og_description' => $request->input('seo_og_description'),
            'schema_json' => $request->input('seo_schema_json'),
            'robots' => $request->input('seo_robots'),
        ], fn ($value) => filled($value));

        if ($request->hasFile('seo_og_image')) {
            $seoData['og_image'] = $this->uploads->replace($project->seo?->og_image, $request->file('seo_og_image'), 'seo');
        }

        if (empty($seoData)) {
            return;
        }

        $project->seo()->updateOrCreate([], $seoData);
    }
}
