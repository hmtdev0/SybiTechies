<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutFeature;
use App\Models\AboutPage;
use App\Models\AboutTimeline;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use App\Models\Statistic;
use App\Models\TeamMember;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('pages.about', [
            'seo' => SeoMeta::forPageKey('about'),
            'siteSettings' => SiteSetting::current(),

            'about' => AboutPage::current(),
            'aboutFeatures' => AboutFeature::query()->orderBy('display_order')->get(),
            'aboutStats' => Statistic::query()->section('about')->active()->ordered()->get(),
            'timeline' => AboutTimeline::query()->ordered()->get(),
            'team' => TeamMember::query()->active()->ordered()->get(),
        ]);
    }
}
