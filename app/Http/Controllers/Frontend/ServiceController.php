<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('pages.services-index', [
            'seo' => SeoMeta::forPageKey('services'),
            'siteSettings' => SiteSetting::current(),
            'services' => Service::query()->active()->ordered()->get(),
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->status, 404);

        return view('pages.service-show', [
            'seo' => $service->seo ?? new \App\Models\SeoMeta(),
            'siteSettings' => SiteSetting::current(),

            'service' => $service->load('features'),
            'otherServices' => Service::query()
                ->active()
                ->ordered()
                ->where('id', '!=', $service->id)
                ->limit(6)
                ->get(),
        ]);
    }
}
