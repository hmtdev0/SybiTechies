<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Centralizes image/file storage for the whole admin panel: every upload
 * field (settings logo, hero image, service icon, project gallery, ...)
 * goes through this service so resizing, naming and cleanup-on-replace
 * behave identically everywhere.
 *
 * Files are stored under public/uploads/{directory}/ and referenced in the
 * database as a path relative to public/ (e.g. "uploads/services/foo.webp"),
 * so views can render them with asset($path).
 */
class ImageUploadService
{
    protected const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    protected const MAX_DIMENSION = 1920;

    public function __construct(protected ImageManager $manager = new ImageManager(new Driver)) {}

    /**
     * Store an uploaded file under public/uploads/{directory}/ and return
     * its relative path. Raster images are re-encoded to WebP and capped
     * at MAX_DIMENSION on the long edge (compression); SVGs and PDFs are
     * stored as-is.
     */
    public function store(UploadedFile $file, string $directory): string
    {
        $targetDir = public_path("uploads/{$directory}");
        File::ensureDirectoryExists($targetDir);

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid()->toString();

        if (in_array($extension, self::IMAGE_EXTENSIONS, true)) {
            $image = $this->manager->read($file->getRealPath());
            $image->scaleDown(self::MAX_DIMENSION, self::MAX_DIMENSION);

            $relativePath = "uploads/{$directory}/{$filename}.webp";
            $image->toWebp(quality: 82)->save(public_path($relativePath));

            return $relativePath;
        }

        // SVG / PDF / anything else — store unmodified.
        $safeExtension = $extension ?: 'bin';
        $relativePath = "uploads/{$directory}/{$filename}.{$safeExtension}";
        $file->move($targetDir, basename($relativePath));

        return $relativePath;
    }

    /**
     * Store a new file and delete the old one (if any). Use this for every
     * "replace this image" field so orphaned files never accumulate.
     */
    public function replace(?string $oldPath, UploadedFile $file, string $directory): string
    {
        $newPath = $this->store($file, $directory);
        $this->delete($oldPath);

        return $newPath;
    }

    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
