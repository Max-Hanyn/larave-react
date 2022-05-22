<?php

namespace App\DTO\Post;

use Spatie\LaravelData\Data;

class NewPostCreationDto extends Data
{

    public function __construct(
        public string $header,
        public string $content,
        public int    $user_id,
        public string $image_src
    )
    {

    }
}
