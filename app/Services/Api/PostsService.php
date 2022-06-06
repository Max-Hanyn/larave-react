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
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

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

    /**
     * @param int $postId
     * @return NewPostDto
     * @throws Exception
     */
    public function getPostById(int $postId): NewPostDto
    {
        $post = Posts::find($postId);

        if (!$post) {
            throw new Exception('No such post');
        }

        $post->image_src = $this->imageService->getImageUrl($post->image_src);
        return NewPostDto::from($post);
    }

    /**
     * @param array $data
     * @return NewPostDto
     * @throws Exception
     */
    public function updatePostById(array $data): NewPostDto
    {

        $post = Posts::find($data['id']);

        if (!$post) {
            throw new Exception('No Post Found');
        }

        $post->header = $data['header'];
        $post->content = $data['content'];
        $post->save();

        return NewPostDto::from($post);
    }

    /**
     * @param int $postId
     * @return NewPostDto
     * @throws Exception
     */
    public function deletePostById(int $postId): NewPostDto
    {

        $post = Posts::find($postId);

        if (!$post) {
            throw new Exception('No Post Found');
        }

        $post->delete();

        return NewPostDto::from($post);

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

    public function getPostsByPage(int $page = 1, int $recordsPerPage = 10): DataCollection
    {
        $users = Posts::paginate($recordsPerPage);

        return NewPostDto::collection($users);

    }

}
