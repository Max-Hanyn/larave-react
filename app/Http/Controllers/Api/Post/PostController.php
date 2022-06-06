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
use App\Services\Api\ImageService;
use App\Services\Api\PostsService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class PostController extends Controller
{
    private PostsService $postsService;
    private ImageService $imageService;

    public function __construct(PostsService $postsService, ImageService $imageService)
    {
        $this->postsService = $postsService;
        $this->imageService = $imageService;
    }

    /**
     * Create Posts
     * @OA\Post (
     *     path="/api/posts",
     *     operationId = "NewPost",
     *     tags={"Posts"},
     *     security={
     *         {"passport": {}},
     *      },
     *     @OA\RequestBody(
     *     required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      property="image_src",
     *                      description="Upload image value",
     *                      type="file",
     *                      format="binary",
     *                   ),
     *                  @OA\Property(
     *                      property="header",
     *                      description="Add post header",
     *                      type="string",
     *                   ),
     *                  @OA\Property(
     *                      property="content",
     *                      description="Add content post",
     *                      type="string",
     *                   ),
     *             )
     *
     *         ),
     *
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="header", type="string", example="title"),
     *              @OA\Property(property="content", type="string", example="content"),
     *              @OA\Property(property="user_id", type="number", example="1"),
     *              @OA\Property(property="imgage_src", type="string", example="/storage/GxrusnxMd4oADTTz00e8aK8rVxuyvRuXIcx3oRca.png"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    /**
     * @param CreatePostRequest $createPostRequest
     * @return JsonResponse
     */
    public function createPost(CreatePostRequest $createPostRequest): JsonResponse
    {

        $post = $createPostRequest->validated();

        $image = $this->imageService->store($createPostRequest->file('image_src'));

        $path = $this->imageService->getImageUrl($image);

        $post['user_id'] = Auth::id();
        $post['image_src'] = $image;

        $post = $this->postsService->createPost(NewPostCreationDto::from($post));

        return response()->json(['post' => $post], 200);
    }

    /**
     * Get Post by id
     * @OA\Get (
     *     path="/api/posts/{id}",
     *     operationId = "GetPostById",
     *     tags={"Posts"},
     *     security={
     *         {"passport": {}},
     *      },
     *     @OA\Parameter(
     *         description="ID of Post",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *            type="integer",
     *            format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="post", type="object",
     *
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="header", type="string", example="title"),
     *                      @OA\Property(property="content", type="string", example="content"),
     *                      @OA\Property(property="user_id", type="number", example="1"),
     *                      @OA\Property(property="imgage_src", type="string", example="/storage/GxrusnxMd4oADTTz00e8aK8rVxuyvRuXIcx3oRca.png"),
     *
     *
     *          )
     * )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="false"),
     *              @OA\Property(property="content", type="string", example="No post found"),
     *          )
     *      )
     * )
     */
    /**
     * @throws Exception
     */
    public function getPost(int $id): JsonResponse
    {

        try {
            $post = $this->postsService->getPostById($id);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'content' => 'No post found'], 404);
        }

        return response()->json(['post' => $post]);
    }

    /**
     * Update Post by id
     * @OA\Put (
     *     path="/api/posts",
     *     operationId = "UpdatePostById",
     *     tags={"Posts"},
     *     security={
     *         {"passport": {}},
     *      },
     *     @OA\Parameter(
     *         description="ID of Post",
     *         in="query",
     *         name="id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *            type="integer",
     *            format="int64"
     *          )
     *      ),
     *     @OA\Parameter(
     *         description="Header of the edit Post",
     *         in="query",
     *         name="header",
     *         required=true,
     *         example="header",
     *         @OA\Schema(
     *            type="string",
     *          )
     *      ),
     *     @OA\Parameter(
     *         description="Content of the edit Post",
     *         in="query",
     *         name="content",
     *         required=true,
     *         example="content",
     *         @OA\Schema(
     *            type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="post", type="object",
     *
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="header", type="string", example="title"),
     *                      @OA\Property(property="content", type="string", example="content"),
     *                      @OA\Property(property="user_id", type="number", example="1"),
     *                      @OA\Property(property="imgage_src", type="string", example="/storage/GxrusnxMd4oADTTz00e8aK8rVxuyvRuXIcx3oRca.png"),
     *
     *
     *          )
     * )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="false"),
     *              @OA\Property(property="content", type="string", example="No post found"),
     *          )
     *      )
     * )
     */
    /**
     * @throws Exception
     */
    public function updatePost(UpdatePostRequest $updatePostRequest): JsonResponse
    {

        $data = $updatePostRequest->validated();

        try {
            $post = $this->postsService->updatePostById($data);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'content' => 'No post found'], 404 );
        }

        return response()->json(['post' => $post]);
    }
    /**
     * Delete Post by id
     * @OA\Delete  (
     *     path="/api/posts/{id}",
     *     operationId = "DeletePostById",
     *     tags={"Posts"},
     *     security={
     *         {"passport": {}},
     *      },
     *     @OA\Parameter(
     *         description="ID of Post",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *            type="integer",
     *            format="int64"
     *          )
     *      ),

     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="post", type="object",
     *
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="header", type="string", example="title"),
     *                      @OA\Property(property="content", type="string", example="content"),
     *                      @OA\Property(property="user_id", type="number", example="1"),
     *                      @OA\Property(property="imgage_src", type="string", example="/storage/GxrusnxMd4oADTTz00e8aK8rVxuyvRuXIcx3oRca.png"),
     *
     *
     *          )
     * )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="false"),
     *              @OA\Property(property="content", type="string", example="No post found"),
     *          )
     *      )
     * )
     */
    /**
     * @throws Exception
     */
    public function deletePost(int $id): JsonResponse
    {

        try {
            $post = $this->postsService->deletePostById($id);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'content' => 'No post found'], 404);
        }

        return response()->json(['post' => $post]);
    }

    /**
     * Get User Post by id
     * @OA\Get (
     *     path="/api/posts/user/{userId}",
     *     operationId = "GetPostsByUserId",
     *     tags={"Posts"},
     *     security={
     *         {"passport": {}},
     *      },
     *     @OA\Parameter(
     *         description="ID of the User",
     *         in="path",
     *         name="userId",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *            type="integer",
     *            format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="post", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="header", type="string", example="title"),
     *                      @OA\Property(property="content", type="string", example="content"),
     *                      @OA\Property(property="user_id", type="number", example="1"),
     *                      @OA\Property(property="imgage_src", type="string", example="/storage/GxrusnxMd4oADTTz00e8aK8rVxuyvRuXIcx3oRca.png"),
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="false"),
     *              @OA\Property(property="content", type="string", example="No post found"),
     *          )
     *      )
     * )
     */
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
            return response()->json(['success' => false, 'content' => $e->getMessage()], 404);
        }

        return response()->json(['posts' => $posts]);
    }

    /**
     * Get Posts by page
     * @OA\Get (
     *     path="/api/posts/list",
     *     operationId = "GetPostsByPage",
     *     tags={"Posts"},
     *     security={
     *         {"passport": {}},
     *      },
     *     @OA\Parameter(
     *         description="ID of the User",
     *         in="query",
     *         name="page",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *            type="integer",
     *            format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="posts", type="object",
     *                  @OA\Property(property="data", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="id", type="number", example=1),
     *                          @OA\Property(property="header", type="string", example="title"),
     *                          @OA\Property(property="content", type="string", example="content"),
     *                          @OA\Property(property="user_id", type="number", example="1"),
     *                          @OA\Property(property="imgage_src", type="string", example="/storage/GxrusnxMd4oADTTz00e8aK8rVxuyvRuXIcx3oRca.png"),
     *                      )
     *                  )
     *              ),
     *             @OA\Property(property="meta", type="object",
     *                   @OA\Property(property="current_page", type="number", example=1),
     *                   @OA\Property(property="first_page_url", type="string", example="http://laravel/api/posts/list?page=1"),
     *                   @OA\Property(property="from", type="number", example=1),
     *                   @OA\Property(property="last_page", type="number", example=1),
     *                   @OA\Property(property="last_page_url", type="string", example="http://laravel/api/posts/list?page=5"),
     *                   @OA\Property(property="next_page_url", type="string", example="http://laravel/api/posts/list?page=2"),
     *                   @OA\Property(property="path", type="string", example="http://laravel/api/posts/list"),
     *                   @OA\Property(property="per_page", type="number", example=10),
     *                   @OA\Property(property="prev_page_url", type="number", example=1),
     *                   @OA\Property(property="to", type="number", example=10),
     *                   @OA\Property(property="total", type="number", example=42),
     *             )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="false"),
     *              @OA\Property(property="content", type="string", example="No post found"),
     *          )
     *      )
     * )
     */
    public function getPostsByPage(Request $request)
    {

        $page = $request->get('page') ?: 1;
        $posts = $this->postsService->getPostsByPage($page);

        return response()->json(['posts' => $posts]);

    }

}
