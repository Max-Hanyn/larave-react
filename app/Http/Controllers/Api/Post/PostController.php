<?php

namespace App\Http\Controllers\Api\Post;

use App\DTO\Post\NewPostCreationDto;
use App\DTO\Post\NewPostDto;
use App\DTO\Post\PostsDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\CreatePostRequest;
use App\Http\Requests\Api\Post\DeletePostRequest;
use App\Http\Requests\Api\Post\GetPostRequest;
use App\Http\Requests\Api\Post\UpdatePostRequest;
use App\Models\Posts;
use App\Models\User;
use App\Services\Api\PostsService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class PostController extends Controller
{
    private PostsService $postsService;

    public function __construct(PostsService $postsService)
    {
        $this->postsService = $postsService;
    }

    /**
     * @param CreatePostRequest $createPostRequest
     * @return JsonResponse
     */
    public function createPost(CreatePostRequest $createPostRequest): JsonResponse
    {

        $post = $createPostRequest->validated();
        $post['user_id'] = Auth::id();

        $post = $this->postsService->createPost(NewPostCreationDto::from($post));

        return response()->json(['post' => $post]);
    }

    /**
     * @throws Exception
     */
    public function getPost(int $id): JsonResponse
    {

        try {
            $post = $this->postsService->getPostById($id);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'content' => 'No post found']);
        }

        return response()->json(['post' => $post]);
    }

    /**
     * @throws Exception
     */
    public function updatePost(UpdatePostRequest $updatePostRequest): JsonResponse
    {

        $data = $updatePostRequest->validated();

        try {
            $post = $this->postsService->updatePostById($data);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'content' => 'No post found']);
        }

        return response()->json(['post' => $post]);
    }

    /**
     * @throws Exception
     */
    public function deletePost(int $id): JsonResponse
    {

        try {
            $post = $this->postsService->deletePostById($id);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'content' => 'No post found']);
        }

        return response()->json(['post' => $post]);
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getUserPosts(int $userId = 0): JsonResponse
    {
        $id = $userId ? $userId : Auth::id();

        try {
            $posts = $this->postsService->getUserPosts($id);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'content' => $e->getMessage()]);
        }

        return response()->json(['posts' => $posts]);
    }

}
