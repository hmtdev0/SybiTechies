<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Testimonial;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function index(Request $request): View
    {
        $this->authorize('view testimonials');

        $testimonials = Testimonial::query()
            ->when($request->filled('search'), fn ($q) => $q->where('client_name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('display_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.testimonials.index', [
            'testimonials' => $testimonials,
            'breadcrumb' => 'Testimonials',
        ]);
    }

    public function create(): View
    {
        $this->authorize('create testimonials');

        return view('admin.testimonials.create', ['breadcrumb' => 'Testimonials — Add New']);
    }

    public function store(TestimonialRequest $request): RedirectResponse
    {
        $this->authorize('create testimonials');

        $data = $request->validated();
        // Editors can create testimonials but not publish them — force
        // unpublished unless they're allowed to publish.
        $data['status'] = $request->user()->can('publish testimonials') && $request->boolean('status', true);

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploads->store($request->file('photo'), 'testimonials');
        }

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial added successfully.');
    }

    public function edit(Testimonial $testimonial): View
    {
        $this->authorize('edit testimonials');

        return view('admin.testimonials.edit', [
            'testimonial' => $testimonial,
            'breadcrumb' => 'Testimonials — Edit',
        ]);
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $this->authorize('edit testimonials');

        $data = $request->validated();
        $data['status'] = $request->user()->can('publish testimonials')
            ? $request->boolean('status', true)
            : $testimonial->status;

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploads->replace($testimonial->photo, $request->file('photo'), 'testimonials');
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->authorize('delete testimonials');

        $this->uploads->delete($testimonial->photo);
        $testimonial->delete();

        return back()->with('success', 'Testimonial deleted.');
    }

    public function toggleStatus(Testimonial $testimonial): RedirectResponse
    {
        $this->authorize('publish testimonials');

        $testimonial->update(['status' => ! $testimonial->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $this->authorize('delete testimonials');

        $ids = (array) $request->input('ids', []);
        $testimonials = Testimonial::query()->whereIn('id', $ids)->get();

        foreach ($testimonials as $testimonial) {
            $this->uploads->delete($testimonial->photo);
        }

        Testimonial::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' testimonials deleted.');
    }
}
