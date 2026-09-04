<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectCategoryRequest;
use App\Models\ProjectCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage settings');

        $categories = ProjectCategory::query()
            ->withCount('projects')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('display_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.project-categories.index', [
            'categories' => $categories,
            'breadcrumb' => 'Project Categories',
        ]);
    }

    public function create(): View
    {
        return view('admin.project-categories.create', ['breadcrumb' => 'Project Categories — Add New']);
    }

    public function store(ProjectCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        ProjectCategory::create($data);

        return redirect()->route('admin.project-categories.index')->with('success', 'Category added successfully.');
    }

    public function edit(ProjectCategory $projectCategory): View
    {
        return view('admin.project-categories.edit', [
            'category' => $projectCategory,
            'breadcrumb' => 'Project Categories — Edit',
        ]);
    }

    public function update(ProjectCategoryRequest $request, ProjectCategory $projectCategory): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $projectCategory->update($data);

        return redirect()->route('admin.project-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(ProjectCategory $projectCategory): RedirectResponse
    {
        $projectCategory->delete();

        return back()->with('success', 'Category deleted.');
    }

    public function toggleStatus(ProjectCategory $projectCategory): RedirectResponse
    {
        $projectCategory->update(['status' => ! $projectCategory->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        ProjectCategory::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' categories deleted.');
    }
}
