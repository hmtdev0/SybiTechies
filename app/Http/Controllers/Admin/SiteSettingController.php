<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteSettingRequest;
use App\Models\SiteSetting;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function edit(): View
    {
        $this->authorize('manage settings');

        return view('admin.settings.edit', [
            'settings' => SiteSetting::current(),
            'breadcrumb' => 'Website Settings',
        ]);
    }

    public function update(SiteSettingRequest $request): RedirectResponse
    {
        $settings = SiteSetting::current();
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploads->replace($settings->logo, $request->file('logo'), 'settings');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $this->uploads->replace($settings->favicon, $request->file('favicon'), 'settings');
        }

        $settings->update($data);

        return back()->with('success', 'Website settings updated successfully.');
    }
}
