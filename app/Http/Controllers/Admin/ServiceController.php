<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function index(Request $request): View
    {
        $this->authorize('view services');

        $services = Service::query()
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status') === 'active'))
            ->orderBy('display_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.services.index', [
            'services' => $services,
            'breadcrumb' => 'Services',
        ]);
    }

    public function create(): View
    {
        $this->authorize('create services');

        return view('admin.services.create', [
            'breadcrumb' => 'Services — Add New',
        ]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $this->authorize('create services');

        $data = $request->safe()->except([
            'features', 'image',
            'seo_title', 'seo_meta_description', 'seo_meta_keywords', 'seo_canonical_url',
            'seo_og_image', 'seo_og_title', 'seo_og_description', 'seo_schema_json', 'seo_robots',
        ]);
        // Editors can create services but not publish them — force unpublished
        // regardless of what the form submitted unless they're allowed to publish.
        $data['status'] = $request->user()->can('publish services') && $request->boolean('status', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data = $this->sanitizeRichText($data);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploads->store($request->file('image'), 'services');
        }

        $service = Service::create($data);

        $this->syncFeatures($service, $request);
        $this->syncSeo($service, $request);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service): View
    {
        $this->authorize('edit services');

        $service->load(['features', 'seo']);

        return view('admin.services.edit', [
            'service' => $service,
            'breadcrumb' => 'Services — Edit',
        ]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $this->authorize('edit services');

        $data = $request->safe()->except([
            'features', 'image',
            'seo_title', 'seo_meta_description', 'seo_meta_keywords', 'seo_canonical_url',
            'seo_og_image', 'seo_og_title', 'seo_og_description', 'seo_schema_json', 'seo_robots',
        ]);
        // Without publish rights, editing never changes publish status either
        // way — it stays whatever it already was, so a typo fix can't
        // accidentally publish (or unpublish) live content.
        $data['status'] = $request->user()->can('publish services')
            ? $request->boolean('status', true)
            : $service->status;
        $data['is_featured'] = $request->boolean('is_featured');
        $data = $this->sanitizeRichText($data);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploads->replace($service->image, $request->file('image'), 'services');
        }

        $service->update($data);

        $this->syncFeatures($service, $request);
        $this->syncSeo($service, $request);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->authorize('delete services');

        $this->uploads->delete($service->image);
        if ($service->seo) {
            $this->uploads->delete($service->seo->og_image);
            $service->seo->delete();
        }

        $service->delete();

        return back()->with('success', 'Service deleted.');
    }

    public function toggleStatus(Service $service): RedirectResponse
    {
        $this->authorize('publish services');

        $service->update(['status' => ! $service->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $this->authorize('delete services');

        $ids = (array) $request->input('ids', []);
        $services = Service::query()->whereIn('id', $ids)->with('seo')->get();

        foreach ($services as $service) {
            $this->uploads->delete($service->image);
            $service->seo?->delete();
        }

        Service::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' services deleted.');
    }

    /**
     * full_description is edited with a Quill rich-text editor and stored as
     * raw HTML. Only an authenticated admin can reach this endpoint, but as
     * defense-in-depth we still strip anything outside Quill's own output
     * vocabulary (same allowlist used for Project description/challenges/solutions).
     */
    protected function sanitizeRichText(array $data): array
    {
        if (empty($data['full_description'])) {
            return $data;
        }

        $allowedTags = '<p><h2><h3><strong><em><u><s><ol><ul><li><blockquote><a><img><br>';

        $html = strip_tags($data['full_description'], $allowedTags);
        $html = preg_replace('/\s(on\w+)\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $html);
        $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '$1=$2#$2', $html);

        $data['full_description'] = $html;

        return $data;
    }

    protected function syncFeatures(Service $service, Request $request): void
    {
        $service->features()->delete();

        $features = collect($request->input('features', []))
            ->map(fn ($text) => trim((string) $text))
            ->filter()
            ->values();

        foreach ($features as $i => $text) {
            $service->features()->create(['feature_text' => $text, 'display_order' => $i + 1]);
        }
    }

    protected function syncSeo(Service $service, Request $request): void
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
            $seoData['og_image'] = $this->uploads->replace($service->seo?->og_image, $request->file('seo_og_image'), 'seo');
        }

        if (empty($seoData)) {
            return;
        }

        $service->seo()->updateOrCreate([], $seoData);
    }
}
