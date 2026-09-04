<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SeoMetaRequest;
use App\Models\SeoMeta;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class SeoMetaController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function index(): View
    {
        $this->authorize('manage settings');

        $pages = collect(SeoMeta::PAGE_KEYS)->map(
            fn (string $key) => SeoMeta::query()->where('page_key', $key)->first()
                ?? new SeoMeta(['page_key' => $key])
        );

        return view('admin.seo.index', [
            'pages' => $pages,
            'breadcrumb' => 'SEO Manager',
        ]);
    }

    public function edit(string $pageKey): View
    {
        $this->ensureValidPageKey($pageKey);

        return view('admin.seo.edit', [
            'seo' => SeoMeta::forPageKey($pageKey),
            'pageKey' => $pageKey,
            'breadcrumb' => 'SEO Manager — '.Str::title($pageKey),
        ]);
    }

    public function update(SeoMetaRequest $request, string $pageKey): RedirectResponse
    {
        $this->ensureValidPageKey($pageKey);

        $seo = SeoMeta::forPageKey($pageKey);
        $data = $request->validated();

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $this->uploads->replace($seo->og_image, $request->file('og_image'), 'seo');
        }

        $seo->update($data);

        return redirect()->route('admin.seo.index')->with('success', 'SEO settings updated successfully.');
    }

    protected function ensureValidPageKey(string $pageKey): void
    {
        abort_unless(in_array($pageKey, SeoMeta::PAGE_KEYS, true), Response::HTTP_NOT_FOUND);
    }
}
