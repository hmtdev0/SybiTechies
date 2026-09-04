<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutPageRequest;
use App\Models\AboutFeature;
use App\Models\AboutPage;
use App\Models\AboutTimeline;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutCmsController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function edit(): View
    {
        $this->authorize('manage settings');

        return view('admin.about-cms.edit', [
            'about' => AboutPage::current(),
            'features' => AboutFeature::query()->orderBy('display_order')->get(),
            'timeline' => AboutTimeline::query()->orderBy('display_order')->get(),
            'breadcrumb' => 'About Page CMS',
        ]);
    }

    public function update(AboutPageRequest $request): RedirectResponse
    {
        $about = AboutPage::current();
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploads->replace($about->image, $request->file('image'), 'about');
        }

        $about->update($data);

        return back()->with('success', 'About page updated successfully.');
    }

    // ---- Features ----

    public function storeFeature(Request $request): RedirectResponse
    {
        AboutFeature::create($this->validateFeature($request));

        return back()->with('success', 'Feature added.');
    }

    public function updateFeature(Request $request, AboutFeature $aboutFeature): RedirectResponse
    {
        $aboutFeature->update($this->validateFeature($request));

        return back()->with('success', 'Feature updated.');
    }

    public function destroyFeature(AboutFeature $aboutFeature): RedirectResponse
    {
        $aboutFeature->delete();

        return back()->with('success', 'Feature removed.');
    }

    // ---- Timeline ----

    public function storeTimeline(Request $request): RedirectResponse
    {
        AboutTimeline::create($this->validateTimeline($request));

        return back()->with('success', 'Timeline entry added.');
    }

    public function updateTimeline(Request $request, AboutTimeline $aboutTimeline): RedirectResponse
    {
        $aboutTimeline->update($this->validateTimeline($request));

        return back()->with('success', 'Timeline entry updated.');
    }

    public function destroyTimeline(AboutTimeline $aboutTimeline): RedirectResponse
    {
        $aboutTimeline->delete();

        return back()->with('success', 'Timeline entry removed.');
    }

    protected function validateFeature(Request $request): array
    {
        return $request->validate([
            'icon' => ['nullable', 'string', 'max:60'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    protected function validateTimeline(Request $request): array
    {
        return $request->validate([
            'year' => ['required', 'string', 'max:20'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
