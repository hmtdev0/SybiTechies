<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $posts = BlogPost::query()
            ->published()
            ->with(['category', 'tags'])
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $request->string('category')));
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('pages.blog-index', [
            'seo' => SeoMeta::forPageKey('blog'),
            'siteSettings' => SiteSetting::current(),

            'posts' => $posts,
            'categories' => BlogCategory::query()->active()->orderBy('display_order')->get(),
        ]);
    }

    public function show(BlogPost $post): View
    {
        abort_unless($post->status === 'published', 404);

        $post->increment('views_count');
        $post->load(['category', 'tags', 'images']);

        return view('pages.blog-show', [
            'seo' => $post->seo ?? new SeoMeta(),
            'siteSettings' => SiteSetting::current(),

            'post' => $post,
            'relatedPosts' => $post->relatedPosts(),
        ]);
    }
}
