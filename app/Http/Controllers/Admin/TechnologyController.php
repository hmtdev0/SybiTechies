<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TechnologyRequest;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TechnologyController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage settings');

        $technologies = Technology::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('display_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.technologies.index', [
            'technologies' => $technologies,
            'breadcrumb' => 'Technologies',
        ]);
    }

    public function create(): View
    {
        return view('admin.technologies.create', ['breadcrumb' => 'Technologies — Add New']);
    }

    public function store(TechnologyRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        Technology::create($data);

        return redirect()->route('admin.technologies.index')->with('success', 'Technology added successfully.');
    }

    public function edit(Technology $technology): View
    {
        return view('admin.technologies.edit', [
            'technology' => $technology,
            'breadcrumb' => 'Technologies — Edit',
        ]);
    }

    public function update(TechnologyRequest $request, Technology $technology): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $technology->update($data);

        return redirect()->route('admin.technologies.index')->with('success', 'Technology updated successfully.');
    }

    public function destroy(Technology $technology): RedirectResponse
    {
        $technology->delete();

        return back()->with('success', 'Technology deleted.');
    }

    public function toggleStatus(Technology $technology): RedirectResponse
    {
        $technology->update(['status' => ! $technology->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        Technology::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' technologies deleted.');
    }
}
