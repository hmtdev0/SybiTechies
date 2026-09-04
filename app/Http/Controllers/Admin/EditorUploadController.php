<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Handles inline image uploads triggered from the Quill rich-text editor
 * (image toolbar button / drag-drop / paste) used on long-form content
 * fields such as Project description/challenges/solutions.
 */
class EditorUploadController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $path = $this->uploads->store($request->file('image'), 'editor');

        return response()->json(['url' => asset($path)]);
    }
}
