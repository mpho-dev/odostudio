<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageSanitizer
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver);
    }

    public function sanitize(UploadedFile $file): ?string
    {
        $path = $file->getPathname();
        $extension = strtolower($file->getClientOriginalExtension());
        $tempPath = storage_path('app/temp_'.uniqid().'.'.$extension);

        try {
            $image = $this->imageManager->read($path);

            if ($extension === 'jpg' || $extension === 'jpeg') {
                $image->toJpeg(90)->save($tempPath);
            } elseif ($extension === 'png') {
                $image->toPng()->save($tempPath);
            } elseif ($extension === 'webp') {
                $image->toWebp(90)->save($tempPath);
            } elseif ($extension === 'gif') {
                $image->toGif()->save($tempPath);
            } else {
                copy($path, $tempPath);
            }

            return $tempPath;
        } catch (Exception $e) {
            Log::warning('Image sanitization failed, using original file', [
                'error' => $e->getMessage(),
                'filename' => $file->getClientOriginalName(),
            ]);

            return null;
        }
    }

    public function sanitizeAndStore(UploadedFile $file, string $directory = 'media'): string
    {
        $filename = $file->getClientOriginalName();
        $destination = $directory.'/'.$filename;

        $sanitizedPath = $this->sanitize($file);

        if ($sanitizedPath !== null) {
            Storage::disk('public')->put($destination, file_get_contents($sanitizedPath));
            unlink($sanitizedPath);
        } else {
            Storage::disk('public')->putFileAs($directory, $file, $filename);
        }

        return $destination;
    }
}
