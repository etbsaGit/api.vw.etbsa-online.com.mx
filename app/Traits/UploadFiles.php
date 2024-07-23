<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait UploadFiles
{
    public function saveImage($base64, $defaultPathFolder)
    {
        // Check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($base64, strpos($base64, ',') + 1);
            // Get file extension
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $fileName = Str::random() . '.' . $type;
        $filePath = $defaultPathFolder . '/' . $fileName;

        // Guardar el archivo en AWS S3
        Storage::disk('s3')->put($filePath, $image);

        return $filePath;
    }
}
