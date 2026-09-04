<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogPostRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogPostImage;
use App\Models\Tag;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function index(Request $request): View
    {
        $this->authorize('view blogs');

        $posts = BlogPost::query()
            ->with('category')
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('category'), fn ($q) => $q->where('blog_category_id', $request->integer('category')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.blog-posts.index', [
            'posts' => $posts,
            'categories' => BlogCategory::query()->orderBy('name')->get(),
            'breadcrumb' => 'Blog Posts',
        ]);
    }

    public function create(): View
    {
        $this->authorize('create blogs');

        return view('admin.blog-posts.create', [
            'categories' => BlogCategory::query()->active()->orderBy('display_order')->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
            'breadcrumb' => 'Blog Posts — Add New',
        ]);
    }

    public function store(BlogPostRequest $request): RedirectResponse
    {
        $this->authorize('create blogs');

        $data = $request->safe()->except([
            'tags', 'new_tags', 'images', 'featured_image',
            'seo_title', 'seo_meta_description', 'seo_meta_keywords', 'seo_canonical_url',
            'seo_og_image', 'seo_og_title', 'seo_og_description', 'seo_schema_json', 'seo_robots',
        ]);
        // Editors can create posts but not publish them — force draft
        // regardless of what the form submitted unless they're allowed to publish.
        if (! $request->user()->can('publish blogs')) {
            $data['status'] = 'draft';
        }
        $data['is_featured'] = $request->boolean('is_featured');
        $data = $this->sanitizeRichTextFields($data);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploads->store($request->file('featured_image'), 'blog');
        }

        $post = BlogPost::create($data);

        $this->syncTags($post, $request);
        $this->appendGalleryImages($post, $request);
        $this->syncSeo($post, $request);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blogPost): View
    {
        $this->authorize('edit blogs');

        $blogPost->load(['tags', 'images', 'seo']);

        return view('admin.blog-posts.edit', [
            'post' => $blogPost,
            'categories' => BlogCategory::query()->active()->orderBy('display_order')->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
            'breadcrumb' => 'Blog Posts — Edit',
        ]);
    }

    public function update(BlogPostRequest $request, BlogPost $blogPost): RedirectResponse
    {
        $this->authorize('edit blogs');

        $data = $request->safe()->except([
            'tags', 'new_tags', 'images', 'featured_image',
            'seo_title', 'seo_meta_description', 'seo_meta_keywords', 'seo_canonical_url',
            'seo_og_image', 'seo_og_title', 'seo_og_description', 'seo_schema_json', 'seo_robots',
        ]);
        // Without publish rights, editing never changes publish status either
        // way — it stays whatever it already was, so a content fix can't
        // accidentally publish (or unpublish) a live post.
        if (! $request->user()->can('publish blogs')) {
            $data['status'] = $blogPost->status;
        }
        $data['is_featured'] = $request->boolean('is_featured');
        $data = $this->sanitizeRichTextFields($data);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploads->replace($blogPost->featured_image, $request->file('featured_image'), 'blog');
        }

        $blogPost->update($data);

        $this->syncTags($blogPost, $request);
        $this->appendGalleryImages($blogPost, $request);
        $this->syncSeo($blogPost, $request);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $this->authorize('delete blogs');

        $this->uploads->delete($blogPost->featured_image);
        $blogPost->images->each(fn (BlogPostImage $image) => $this->uploads->delete($image->image_path));
        if ($blogPost->seo) {
            $this->uploads->delete($blogPost->seo->og_image);
            $blogPost->seo->delete();
        }

        $blogPost->delete();

        return back()->with('success', 'Blog post deleted.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $this->authorize('delete blogs');

        $ids = (array) $request->input('ids', []);
        $posts = BlogPost::query()->whereIn('id', $ids)->with('images', 'seo')->get();

        foreach ($posts as $post) {
            $this->uploads->delete($post->featured_image);
            $post->images->each(fn (BlogPostImage $image) => $this->uploads->delete($image->image_path));
            $post->seo?->delete();
        }

        BlogPost::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' blog posts deleted.');
    }

    public function destroyImage(BlogPost $blogPost, BlogPostImage $blogPostImage): RedirectResponse
    {
        $this->authorize('edit blogs');

        abort_unless($blogPostImage->blog_post_id === $blogPost->id, 404);

        $this->uploads->delete($blogPostImage->image_path);
        $blogPostImage->delete();

        return back()->with('success', 'Gallery image removed.');
    }

    /**
     * The content field is edited with a rich-text (Quill) editor and stored/
     * rendered as raw HTML. Only an authenticated admin can reach this
     * endpoint, but as a defense-in-depth measure we still strip anything
     * outside Quill's own output vocabulary (no <script>/<iframe>/event-
     * handler attributes/javascript: URLs can slip through a pasted snippet).
     */
    protected function sanitizeRichTextFields(array $data): array
    {
        $allowedTags = '<p><h2><h3><strong><em><u><s><ol><ul><li><blockquote><a><img><br>';

        foreach (['content'] as $field) {
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

    /**
     * Selected existing tags (by id, from the multi-select) are combined
     * with any brand-new comma-separated tag names typed into the free-text
     * field — new names are firstOrCreate'd, then both sets are synced.
     */
    protected function syncTags(BlogPost $post, Request $request): void
    {
        $tagIds = collect($request->input('tags', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        $newNames = collect(explode(',', (string) $request->input('new_tags', '')))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique();

        foreach ($newNames as $name) {
            $tag = Tag::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)]);
            $tagIds->push($tag->id);
        }

        $post->tags()->sync($tagIds->unique()->values()->all());
    }

    protected function appendGalleryImages(BlogPost $post, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $nextOrder = (int) $post->images()->max('display_order') + 1;

        foreach ($request->file('images') as $i => $file) {
            $path = $this->uploads->store($file, 'blog');
            $post->images()->create(['image_path' => $path, 'display_order' => $nextOrder + $i]);
        }
    }

    protected function syncSeo(BlogPost $post, Request $request): void
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
            $seoData['og_image'] = $this->uploads->replace($post->seo?->og_image, $request->file('seo_og_image'), 'seo');
        }

        if (empty($seoData)) {
            return;
        }

        $post->seo()->updateOrCreate([], $seoData);
    }
}
