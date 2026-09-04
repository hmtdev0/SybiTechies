<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobApplicationRequest;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function index(): View
    {
        return view('pages.careers-index', [
            'seo' => SeoMeta::forPageKey('careers'),
            'siteSettings' => SiteSetting::current(),
            'jobOpenings' => JobOpening::query()->active()->ordered()->get(),
        ]);
    }

    public function show(JobOpening $jobOpening): View
    {
        abort_unless($jobOpening->status, 404);

        return view('pages.career-show', [
            'seo' => new SeoMeta(),
            'siteSettings' => SiteSetting::current(),
            'jobOpening' => $jobOpening,
        ]);
    }

    public function apply(JobApplicationRequest $request, JobOpening $jobOpening): RedirectResponse
    {
        abort_unless($jobOpening->status, 404);

        $data = $request->safe()->except('resume');
        $data['job_opening_id'] = $jobOpening->id;
        $data['resume_path'] = $this->uploads->store($request->file('resume'), 'resumes');

        JobApplication::create($data);

        return back()
            ->with('success', "Application received — we'll be in touch if it's a match.")
            ->withFragment('apply');
    }
}
