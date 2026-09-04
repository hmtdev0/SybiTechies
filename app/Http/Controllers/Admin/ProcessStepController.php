<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProcessStepRequest;
use App\Models\ProcessStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcessStepController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage settings');

        $processSteps = ProcessStep::query()
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->string('search').'%'))
            ->orderBy('display_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.process-steps.index', [
            'processSteps' => $processSteps,
            'breadcrumb' => 'Development Process',
        ]);
    }

    public function create(): View
    {
        return view('admin.process-steps.create', ['breadcrumb' => 'Development Process — Add New']);
    }

    public function store(ProcessStepRequest $request): RedirectResponse
    {
        ProcessStep::create($request->validated());

        return redirect()->route('admin.process-steps.index')->with('success', 'Process step added successfully.');
    }

    public function edit(ProcessStep $processStep): View
    {
        return view('admin.process-steps.edit', [
            'processStep' => $processStep,
            'breadcrumb' => 'Development Process — Edit',
        ]);
    }

    public function update(ProcessStepRequest $request, ProcessStep $processStep): RedirectResponse
    {
        $processStep->update($request->validated());

        return redirect()->route('admin.process-steps.index')->with('success', 'Process step updated successfully.');
    }

    public function destroy(ProcessStep $processStep): RedirectResponse
    {
        $processStep->delete();

        return back()->with('success', 'Process step deleted.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        ProcessStep::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' process steps deleted.');
    }
}
