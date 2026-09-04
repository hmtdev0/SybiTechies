<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JobOpeningRequest;
use App\Models\JobOpening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobOpeningController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage settings');

        $jobOpenings = JobOpening::query()
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status') === 'active'))
            ->orderBy('display_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.job-openings.index', [
            'jobOpenings' => $jobOpenings,
            'breadcrumb' => 'Job Openings',
        ]);
    }

    public function create(): View
    {
        return view('admin.job-openings.create', [
            'breadcrumb' => 'Job Openings — Add New',
        ]);
    }

    public function store(JobOpeningRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['description', 'requirements']);
        $data['status'] = $request->boolean('status', true);
        $data = $this->sanitizeRichText($request, $data);

        JobOpening::create($data);

        return redirect()->route('admin.job-openings.index')->with('success', 'Job opening created successfully.');
    }

    public function edit(JobOpening $jobOpening): View
    {
        return view('admin.job-openings.edit', [
            'jobOpening' => $jobOpening,
            'breadcrumb' => 'Job Openings — Edit',
        ]);
    }

    public function update(JobOpeningRequest $request, JobOpening $jobOpening): RedirectResponse
    {
        $data = $request->safe()->except(['description', 'requirements']);
        $data['status'] = $request->boolean('status', true);
        $data = $this->sanitizeRichText($request, $data);

        $jobOpening->update($data);

        return redirect()->route('admin.job-openings.index')->with('success', 'Job opening updated successfully.');
    }

    public function destroy(JobOpening $jobOpening): RedirectResponse
    {
        $jobOpening->delete();

        return back()->with('success', 'Job opening deleted.');
    }

    public function toggleStatus(JobOpening $jobOpening): RedirectResponse
    {
        $jobOpening->update(['status' => ! $jobOpening->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);

        JobOpening::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' job openings deleted.');
    }

    /**
     * description/requirements are edited with a Quill rich-text editor and
     * stored as raw HTML — strip anything outside Quill's own output
     * vocabulary (same allowlist used for Project/Service rich-text fields).
     */
    protected function sanitizeRichText(Request $request, array $data): array
    {
        $allowedTags = '<p><h2><h3><strong><em><u><s><ol><ul><li><blockquote><a><img><br>';

        foreach (['description', 'requirements'] as $field) {
            if (empty($request->input($field))) {
                continue;
            }

            $html = strip_tags($request->input($field), $allowedTags);
            $html = preg_replace('/\s(on\w+)\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $html);
            $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '$1=$2#$2', $html);

            $data[$field] = $html;
        }

        return $data;
    }
}
