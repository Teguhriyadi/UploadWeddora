<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageHelper
{
    public static function uploadBase64ToS3($base64Image, $folder = 'selfie', $maxWidth = 800, $quality = 70)
    {
        if (!$base64Image) return null;

        // Hapus prefix base64
        $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);
        $imageData = base64_decode($imageData);

        $filename = uniqid('img_') . '.jpg';

        $image = Image::make($imageData)
            ->resize($maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('jpg', $quality);

        Storage::disk('s3')->put(
            $folder . '/' . $filename,
            (string) $image,
            'public'
        );

        return $filename;
    }
}
