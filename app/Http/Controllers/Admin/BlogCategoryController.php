<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage settings');

        $categories = BlogCategory::query()
            ->withCount('posts')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('display_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.blog-categories.index', [
            'categories' => $categories,
            'breadcrumb' => 'Blog Categories',
        ]);
    }

    public function create(): View
    {
        return view('admin.blog-categories.create', ['breadcrumb' => 'Blog Categories — Add New']);
    }

    public function store(BlogCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        BlogCategory::create($data);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category added successfully.');
    }

    public function edit(BlogCategory $blogCategory): View
    {
        return view('admin.blog-categories.edit', [
            'category' => $blogCategory,
            'breadcrumb' => 'Blog Categories — Edit',
        ]);
    }

    public function update(BlogCategoryRequest $request, BlogCategory $blogCategory): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $blogCategory->update($data);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $blogCategory): RedirectResponse
    {
        $blogCategory->delete();

        return back()->with('success', 'Category deleted.');
    }

    public function toggleStatus(BlogCategory $blogCategory): RedirectResponse
    {
        $blogCategory->update(['status' => ! $blogCategory->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        BlogCategory::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' categories deleted.');
    }
}
