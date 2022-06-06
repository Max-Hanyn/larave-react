<?php

namespace App\Services\Api;

use Illuminate\Http\UploadedFile;

class ImageService
{
    const IMG_PATH = 'storage';

    /**
     * @param array|UploadedFile|UploadedFile[]|null $image
     */
    public function store(array|UploadedFile|null $image): string{

        $path = $image->store(self::IMG_PATH);

        return $path;
    }

    public function getImageUrl(string $image): string{

        return asset($image);

    }

}
