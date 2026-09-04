<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaFileRequest;
use App\Models\MediaFile;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaFileController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function index(Request $request): View
    {
        $mediaFiles = MediaFile::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('created_at', 'desc')
            ->paginate(24)
            ->withQueryString();

        return view('admin.media.index', [
            'mediaFiles' => $mediaFiles,
            'breadcrumb' => 'Media Library',
        ]);
    }

    public function store(MediaFileRequest $request): RedirectResponse
    {
        $this->authorize('upload media');

        $files = $request->file('files');
        $count = 0;

        foreach ($files as $file) {
            $path = $this->imageUploadService->store($file, 'media');

            MediaFile::create([
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);

            $count++;
        }

        return back()->with('success', $count.' file(s) uploaded successfully.');
    }

    public function destroy(MediaFile $mediaFile): RedirectResponse
    {
        $this->authorize('delete media');

        $this->imageUploadService->delete($mediaFile->file_path);
        $mediaFile->delete();

        return back()->with('success', 'File deleted.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $this->authorize('delete media');

        $ids = (array) $request->input('ids', []);

        $mediaFiles = MediaFile::query()->whereIn('id', $ids)->get();

        foreach ($mediaFiles as $mediaFile) {
            $this->imageUploadService->delete($mediaFile->file_path);
        }

        MediaFile::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' file(s) deleted.');
    }
}
