<?php

namespace Tests\Unit;

use App\DTO\Post\PostsDto;
use App\Services\Api\PostsService;
use PHPUnit\Framework\TestCase;

class PostServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {

        $postService = new PostsService();

        $post = [
            'header' => 'test',
            'content' => 'content',
            'image_src' => 'image',
            'user_id' => 10
        ];

        $post = $postService->createPost(PostsDto::fromArray($post));

        $this->assertEquals('test', $post->header);
    }
}
