<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomScriptRequest;
use App\Models\CustomScript;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomScriptController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage settings');

        $scripts = CustomScript::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('placement'), fn ($q) => $q->where('placement', $request->string('placement')))
            ->orderBy('display_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.custom-scripts.index', [
            'scripts' => $scripts,
            'breadcrumb' => 'Custom Scripts',
        ]);
    }

    public function create(): View
    {
        $this->authorize('manage settings');

        return view('admin.custom-scripts.create', ['breadcrumb' => 'Custom Scripts — Add New']);
    }

    public function store(CustomScriptRequest $request): RedirectResponse
    {
        $this->authorize('manage settings');

        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        CustomScript::create($data);

        return redirect()->route('admin.custom-scripts.index')->with('success', 'Script added successfully.');
    }

    public function edit(CustomScript $customScript): View
    {
        $this->authorize('manage settings');

        return view('admin.custom-scripts.edit', [
            'script' => $customScript,
            'breadcrumb' => 'Custom Scripts — Edit',
        ]);
    }

    public function update(CustomScriptRequest $request, CustomScript $customScript): RedirectResponse
    {
        $this->authorize('manage settings');

        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $customScript->update($data);

        return redirect()->route('admin.custom-scripts.index')->with('success', 'Script updated successfully.');
    }

    public function destroy(CustomScript $customScript): RedirectResponse
    {
        $this->authorize('manage settings');

        $customScript->delete();

        return back()->with('success', 'Script deleted.');
    }

    public function toggleStatus(CustomScript $customScript): RedirectResponse
    {
        $this->authorize('manage settings');

        $customScript->update(['status' => ! $customScript->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $this->authorize('manage settings');

        $ids = (array) $request->input('ids', []);
        CustomScript::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' script(s) deleted.');
    }
}
