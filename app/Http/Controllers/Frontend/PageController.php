<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use Illuminate\View\View;

class PageController extends Controller
{
    public function privacy(): View
    {
        return view('pages.privacy-policy', [
            'seo' => SeoMeta::forPageKey('privacy-policy'),
            'siteSettings' => SiteSetting::current(),
        ]);
    }

    public function terms(): View
    {
        return view('pages.terms', [
            'seo' => SeoMeta::forPageKey('terms-conditions'),
            'siteSettings' => SiteSetting::current(),
        ]);
    }
}
