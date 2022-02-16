<?php

namespace App\Http\Controllers\Api\User;

use App\DTO\User\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateUserRequest;
use App\Http\Requests\Api\User\UpdateUserRoleRequest;
use App\Models\User;
use App\Services\Api\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

    }

    public function getUser(int $userId = null): JsonResponse
    {
        $authUserId = $userId ?: Auth::id();

        try {
            $user = $this->userService->getUserById($authUserId)->toArray();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['user' => $user]);
    }

    public function updateUser(UpdateUserRequest $request, int $userId = null): JsonResponse
    {
        $userId = $userId ?: Auth::id();

        $params = $request->validated();

        $user = $this->userService->updateUserById($userId, $params);

        return response()->json(['user' => $user]);


    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllUsers(Request $request): JsonResponse
    {

        $page = $request->get('page') ?: 1;

        $users = $this->userService->getUsersByPage($page);

        return response()->json(['users' => $users]);

    }

    /**
     * @param UpdateUserRoleRequest $request
     * @return JsonResponse
     */
    public function updateUserRole(UpdateUserRoleRequest $request): JsonResponse
    {

        $roleId = $request->get('role_id');
        $userId = $request->get('user_id');

        $user = $this->userService->updateUserRoleById($userId, $roleId);

        return response()->json(['user' => $user]);
    }


    /**
     * @param int $friendId
     * @return JsonResponse
     */
    public function addUserFriend(int $friendId): JsonResponse
    {

        $userId = Auth::id();

        try {
            $this->userService->addFriend($userId, $friendId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['user' => $friendId]);

    }

    public function acceptUserFriend(int $friendId): JsonResponse
    {
        $userId = Auth::id();

        try {
            $this->userService->acceptFriend($userId, $friendId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['user' => $friendId]);
    }

    public function listOfUserFriends(int $userId = 0): JsonResponse
    {
        $id = $userId ? $userId : Auth::id();
        $user = User::find($id);
        $friends = $user->friends();
        return response()->json(['friends' => $friends]);
    }

    public function listOfUserRequestFriends(int $userId = 0): JsonResponse
    {
        $id = $userId ? $userId : Auth::id();
        $user = User::find($id);
        $friends = $user->friendRequests();
        return response()->json(['friends' => $friends]);
    }

    public function deleteFriend($id){
        $user = User::find(Auth::id())->deleteFriend($id);
        return response()->json(['success' => true]);

    }
}
