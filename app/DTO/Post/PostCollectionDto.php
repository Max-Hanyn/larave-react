<?php

namespace App\DTO\Post;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use App\DTO\Post\NewPostDto;

class PostCollectionDto extends Data
{
    public function __construct(
        #[DataCollectionOf(NewPostDto::class)]
        public DataCollection $posts,
    ) {
    }
}
