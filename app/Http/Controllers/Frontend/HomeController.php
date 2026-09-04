<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutFeature;
use App\Models\AboutPage;
use App\Models\HomeHero;
use App\Models\Industry;
use App\Models\ProcessStep;
use App\Models\Project;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Statistic;
use App\Models\Technology;
use App\Models\Testimonial;
use App\Models\TrustedCompany;
use App\Models\WhyChooseUsItem;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $hero = HomeHero::current();

        return view('pages.home', [
            'seo' => SeoMeta::forPageKey('home'),
            'siteSettings' => SiteSetting::current(),

            'hero' => $hero,
            'heroStats' => $hero->stats,
            'trustedCompanies' => TrustedCompany::query()->active()->ordered()->get(),

            'about' => AboutPage::current(),
            'aboutFeatures' => AboutFeature::query()->orderBy('display_order')->get(),
            'aboutStats' => Statistic::query()->section('about')->active()->ordered()->get(),

            'services' => Service::query()->active()->ordered()->limit(6)->get(),
            'whyUsItems' => WhyChooseUsItem::query()->active()->ordered()->get(),
            'processSteps' => ProcessStep::query()->orderBy('display_order')->get(),
            'technologies' => Technology::query()->active()->ordered()->get(),

            'projects' => Project::query()->active()->featured()->ordered()->with(['category', 'technologies'])->get(),

            'homeStats' => Statistic::query()->section('home')->active()->ordered()->get(),
            'industries' => Industry::query()->active()->ordered()->get(),
            'testimonials' => Testimonial::query()->active()->ordered()->get(),
        ]);
    }
}
