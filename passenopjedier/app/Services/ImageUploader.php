<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ImageUploader
{
    public function uploadImage(UploadedFile $file, string $type): string
    {
        $image = file_get_contents($file->getRealPath());
        return $image;
    }
}
