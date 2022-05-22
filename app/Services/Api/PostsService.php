<?php

namespace App\Services\Api;

use App\DTO\Post\NewPostCreationDto;
use App\DTO\Post\NewPostDto;
use App\DTO\Post\PostCollectionDto;
use App\DTO\Post\PostsDto;
use App\Models\Posts;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\DataCollection;

class PostsService
{

    /**
     * @param NewPostCreationDto $postsDto
     * @return NewPostDto
     */
    public function createPost(NewPostCreationDto $postsDto): NewPostDto
    {

        $post = Posts::create([
            'header' => $postsDto->header,
            'content' => $postsDto->content,
            'image_src' => $postsDto->image_src,
            'user_id' => $postsDto->user_id,

        ])->getData();

        return $post;
    }

    public function getPostById(int $postId): PostsDto
    {
        $post = Posts::find($postId);

        if (!$post) {
            throw new Exception('No such role');
        }
        return PostsDto::fromModel($post);
    }

    /**
     * @param array $data
     * @return PostsDto
     * @throws Exception
     */
    public function updatePostById(array $data): PostsDto
    {

        $post = Posts::find($data['id']);

        if (!$post) {
            throw new Exception('No Post Found');
        }

        $post->header = $data['header'];
        $post->content = $data['content'];
        $post->save();

        return PostsDto::fromModel($post);
    }

    /**
     * @param int $postId
     * @return PostsDto
     * @throws Exception
     */
    public function deletePostById(int $postId): PostsDto
    {

        $post = Posts::find($postId);

        if (!$post) {
            throw new Exception('No Post Found');
        }

        $post->delete();

        return PostsDto::fromModel($post);

    }

    /**
     * @param int $userId
     * @return DataCollection
     * @throws Exception
     */
    public function getUserPosts(int $userId): DataCollection
    {

        $user = User::find($userId);

        if (!$user) {
            throw new Exception('No User Found');
        }

        $posts = $user->posts()->get();

        return NewPostDto::collection($posts);
    }

}
