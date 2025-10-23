<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageUploadHelper
{
    public static function processAndStore(
        UploadedFile $file,
        string $directory = 'uploaded',
        ?int $maxWidth = 400,
        string $disk = 'public'
    ): string {
        $img = Image::make($file);

        if ($maxWidth) {
            $img->resize($maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $extension = $file->getClientOriginalExtension();
        $filename = uniqid() . '.' . $extension;
        $path = $directory . '/' . $filename;

        Storage::disk($disk)->put($path, (string) $img->encode());

        return $path;
    }

    public static function processAndStoreToDirectory(
        UploadedFile $file,
        string $fullPath,
        ?int $maxWidth = 400,
        string $disk = 'public'
    ): string {
        $img = Image::make($file);

        if ($maxWidth) {
            $img->resize($maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        Storage::disk($disk)->put($fullPath, (string) $img->encode());

        return $fullPath;
    }
}
