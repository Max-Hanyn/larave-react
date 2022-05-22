<?php

namespace App\DTO\Post;

use App\Models\Posts;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class PostsDto extends DataTransferObject
{
    public string $header;

    public string $content;

    public int $user_id;

    public string $image_src;

    /**
     * @param array $post
     * @return static
     * @throws UnknownProperties
     */
    public static function fromArray(array $post): static
    {
        return new static([
            'header' => $post['header'],
            'content' => $post['content'],
            'image_src' => $post['image_src'],
            'user_id' => $post['user_id'],
        ]);
    }

    public static function fromModel(Posts $post): static
    {
        return new static([
            'header' => $post->header,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'image_src' => $post->image_src,
        ]);
    }

}
