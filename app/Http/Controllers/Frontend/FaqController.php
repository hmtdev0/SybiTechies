<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('pages.faq', [
            'seo' => SeoMeta::forPageKey('faq'),
            'siteSettings' => SiteSetting::current(),
            'faqs' => Faq::query()->active()->ordered()->get(),
        ]);
    }
}
