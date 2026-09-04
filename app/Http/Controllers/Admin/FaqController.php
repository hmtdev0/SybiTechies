<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage settings');

        $faqs = Faq::query()
            ->when($request->filled('search'), fn ($q) => $q->where('question', 'like', '%'.$request->string('search').'%'))
            ->orderBy('display_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.faqs.index', [
            'faqs' => $faqs,
            'breadcrumb' => 'FAQs',
        ]);
    }

    public function create(): View
    {
        return view('admin.faqs.create', ['breadcrumb' => 'FAQs — Add New']);
    }

    public function store(FaqRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ added successfully.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', [
            'faq' => $faq,
            'breadcrumb' => 'FAQs — Edit',
        ]);
    }

    public function update(FaqRequest $request, Faq $faq): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status', true);

        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return back()->with('success', 'FAQ deleted.');
    }

    public function toggleStatus(Faq $faq): RedirectResponse
    {
        $faq->update(['status' => ! $faq->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        Faq::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' FAQs deleted.');
    }
}
