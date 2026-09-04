<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndustryRequest;
use App\Models\Industry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndustryController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage settings');

        $industries = Industry::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('display_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.industries.index', [
            'industries' => $industries,
            'breadcrumb' => 'Industries',
        ]);
    }

    public function create(): View
    {
        return view('admin.industries.create', ['breadcrumb' => 'Industries — Add New']);
    }

    public function store(IndustryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        Industry::create($data);

        return redirect()->route('admin.industries.index')->with('success', 'Industry added successfully.');
    }

    public function edit(Industry $industry): View
    {
        return view('admin.industries.edit', [
            'industry' => $industry,
            'breadcrumb' => 'Industries — Edit',
        ]);
    }

    public function update(IndustryRequest $request, Industry $industry): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $industry->update($data);

        return redirect()->route('admin.industries.index')->with('success', 'Industry updated successfully.');
    }

    public function destroy(Industry $industry): RedirectResponse
    {
        $industry->delete();

        return back()->with('success', 'Industry deleted.');
    }

    public function toggleStatus(Industry $industry): RedirectResponse
    {
        $industry->update(['status' => ! $industry->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        Industry::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' industries deleted.');
    }
}
