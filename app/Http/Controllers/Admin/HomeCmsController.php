<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomeHeroRequest;
use App\Models\HomeHero;
use App\Models\HomeHeroStat;
use App\Models\Statistic;
use App\Models\TrustedCompany;
use App\Models\WhyChooseUsItem;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeCmsController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function edit(): View
    {
        $this->authorize('manage settings');

        $hero = HomeHero::current();

        return view('admin.home-cms.edit', [
            'hero' => $hero,
            'heroStats' => $hero->stats,
            'mainStats' => Statistic::query()->section('home')->orderBy('display_order')->get(),
            'trustedCompanies' => TrustedCompany::query()->orderBy('display_order')->get(),
            'whyUsItems' => WhyChooseUsItem::query()->orderBy('display_order')->get(),
            'breadcrumb' => 'Home Page CMS',
        ]);
    }

    public function updateHero(HomeHeroRequest $request): RedirectResponse
    {
        $hero = HomeHero::current();
        $data = $request->validated();
        $data['typed_words'] = $request->typedWordsArray();

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploads->replace($hero->image, $request->file('image'), 'hero');
        }

        $hero->update($data);

        return back()->with('success', 'Hero section updated successfully.');
    }

    // ---- Hero floating stats (3 cards) ----

    public function storeHeroStat(Request $request): RedirectResponse
    {
        $data = $this->validateStatLike($request, withSuffix: true);
        $data['home_hero_id'] = HomeHero::current()->id;
        HomeHeroStat::create($data);

        return back()->with('success', 'Floating stat added.');
    }

    public function updateHeroStat(Request $request, HomeHeroStat $heroStat): RedirectResponse
    {
        $heroStat->update($this->validateStatLike($request, withSuffix: true));

        return back()->with('success', 'Floating stat updated.');
    }

    public function destroyHeroStat(HomeHeroStat $heroStat): RedirectResponse
    {
        $heroStat->delete();

        return back()->with('success', 'Floating stat removed.');
    }

    // ---- Main statistics band ----

    public function storeStatistic(Request $request): RedirectResponse
    {
        $data = $this->validateStatLike($request, withSuffix: true, withStatus: true);
        $data['section'] = 'home';
        Statistic::create($data);

        return back()->with('success', 'Statistic added.');
    }

    public function updateStatistic(Request $request, Statistic $statistic): RedirectResponse
    {
        $statistic->update($this->validateStatLike($request, withSuffix: true, withStatus: true));

        return back()->with('success', 'Statistic updated.');
    }

    public function destroyStatistic(Statistic $statistic): RedirectResponse
    {
        $statistic->delete();

        return back()->with('success', 'Statistic removed.');
    }

    // ---- Trusted companies ----

    public function storeTrustedCompany(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);
        $data['status'] = $request->boolean('status', true);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploads->store($request->file('logo'), 'logos');
        }

        TrustedCompany::create($data);

        return back()->with('success', 'Trusted company added.');
    }

    public function updateTrustedCompany(Request $request, TrustedCompany $trustedCompany): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);
        $data['status'] = $request->boolean('status', true);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploads->replace($trustedCompany->logo, $request->file('logo'), 'logos');
        }

        $trustedCompany->update($data);

        return back()->with('success', 'Trusted company updated.');
    }

    public function destroyTrustedCompany(TrustedCompany $trustedCompany): RedirectResponse
    {
        $this->uploads->delete($trustedCompany->logo);
        $trustedCompany->delete();

        return back()->with('success', 'Trusted company removed.');
    }

    // ---- Why choose us items ----

    public function storeWhyUs(Request $request): RedirectResponse
    {
        WhyChooseUsItem::create($this->validateIconTextItem($request));

        return back()->with('success', 'Item added.');
    }

    public function updateWhyUs(Request $request, WhyChooseUsItem $whyChooseUsItem): RedirectResponse
    {
        $whyChooseUsItem->update($this->validateIconTextItem($request));

        return back()->with('success', 'Item updated.');
    }

    public function destroyWhyUs(WhyChooseUsItem $whyChooseUsItem): RedirectResponse
    {
        $whyChooseUsItem->delete();

        return back()->with('success', 'Item removed.');
    }

    // ---- Shared small-form validation helpers ----

    protected function validateStatLike(Request $request, bool $withSuffix = false, bool $withStatus = false): array
    {
        $rules = [
            'icon' => ['nullable', 'string', 'max:60'],
            'number' => ['required', 'integer', 'min:0'],
            'label' => ['required', 'string', 'max:100'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ];

        if ($withSuffix) {
            $rules['suffix'] = ['nullable', 'string', 'max:10'];
        }
        if ($withStatus) {
            $rules['status'] = ['nullable', 'boolean'];
        }

        $data = $request->validate($rules);

        if ($withStatus) {
            $data['status'] = $request->boolean('status', true);
        }

        return $data;
    }

    protected function validateIconTextItem(Request $request): array
    {
        $data = $request->validate([
            'icon' => ['nullable', 'string', 'max:60'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);
        $data['status'] = $request->boolean('status', true);

        return $data;
    }
}
