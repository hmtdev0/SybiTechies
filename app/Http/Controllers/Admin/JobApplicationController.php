<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function index(Request $request): View
    {
        $this->authorize('manage settings');

        $applications = JobApplication::query()
            ->with('jobOpening')
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->string('search').'%';
                $q->where(fn ($q2) => $q2->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('is_read', $request->string('status') === 'read'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.job-applications.index', [
            'applications' => $applications,
            'unreadCount' => JobApplication::query()->unread()->count(),
            'breadcrumb' => 'Job Applications',
        ]);
    }

    public function show(JobApplication $jobApplication): View
    {
        if (! $jobApplication->is_read) {
            $jobApplication->update(['is_read' => true]);
        }

        return view('admin.job-applications.show', [
            'application' => $jobApplication->load('jobOpening'),
            'breadcrumb' => 'Job Applications — View',
        ]);
    }

    public function toggleRead(JobApplication $jobApplication): RedirectResponse
    {
        $jobApplication->update(['is_read' => ! $jobApplication->is_read]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(JobApplication $jobApplication): RedirectResponse
    {
        $this->uploads->delete($jobApplication->resume_path);
        $jobApplication->delete();

        return redirect()->route('admin.job-applications.index')->with('success', 'Application deleted.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        $applications = JobApplication::query()->whereIn('id', $ids)->get();

        foreach ($applications as $application) {
            $this->uploads->delete($application->resume_path);
        }

        JobApplication::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' applications deleted.');
    }
}
