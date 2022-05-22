<?php

namespace App\DTO\Post;

use Spatie\LaravelData\Data;

class NewPostDto extends Data
{

    public function __construct(
        public int $id,
        public string $header,
        public string $content,
        public int    $user_id,
        public string $image_src
    )
    {

    }
}
