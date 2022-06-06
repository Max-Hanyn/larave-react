<?php

namespace Tests\Unit;

use App\DTO\Post\NewPostCreationDto;
use App\DTO\Post\PostsDto;
use App\Models\Posts;
use App\Services\Api\PostsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\TestCase;

class PostServiceTest extends TestCase
{
    use RefreshDatabase;

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

        $dto = NewPostCreationDto::from($post);

        $post = $postService->createPost($dto);

        $this->assertEquals('test', $post->header);
    }

    /**
     * @throws \Exception
     */
    public function test_getPostById()
    {

        $postFactory = Posts::factory()->make();
        $id = $postFactory->header;

        $postService = new PostsService();


        $post = $postService->getPostById(1);

        $this->assertEquals('header123', $post->header);
    }
}
