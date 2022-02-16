<?php

namespace App\Services\Api;

use App\DTO\User\UserCreationDto;
use App\DTO\User\UserDto;
use App\DTO\User\UserListDTO;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Roles as RoleModel;
use JetBrains\PhpStorm\ArrayShape;

class UserService
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function create(UserCreationDto $userCreationDto): UserDto
    {
        $user = User::create([
            'name' => $userCreationDto->name,
            'email' => $userCreationDto->email,
            'password' => Hash::make($userCreationDto->password),
            'role_id' => $this->roleService->getRoleIdBySlug(RoleService::USER_ROLE_SLUG),
            'firstName' => '',
            'lastName' => '',
            'avatar' => '',
            'address' => ''

        ]);

        return UserDto::fromModel($user);

    }

    public function createToken(UserDto $userDto): string
    {
        return User::find($userDto->id)->createToken('name')->accessToken;
    }

   function getUserById(int $id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new Exception('No user with such id');
        }

        return $user;


    }

    /**
     * @return UserDto[] $users
     */
    public function getAllUsers(): array
    {
        $users = User::all();

        $usersDTO = [];
        $users->each(function ($item, $key) use (&$usersDTO) {
            $usersDTO[] = UserDto::fromModel($item);
        });

        return $usersDTO;

    }

    /**
     * @param int $page
     * @param int $recordsPerPage
     */
    #[ArrayShape(['data' => "UserDto[]", 'meta' => "array"])]
    public function getUsersByPage(int $page = 1, int $recordsPerPage = 10)
    {
        $users = User::All();

        $id = Auth::id();
        foreach ($users as $key => $user) {
            if ($user->isFriendWith(Auth::id())){
                $users->forget($key);

            }

        }

        return $users->toArray();

    }

    /**
     * @param int $userId
     * @param array $params
     * @return UserDto
     */
    public function updateUserById(int $userId, array $params): UserDto
    {
        $user = User::find($userId);
        $user->name = $params['name'];
        $user->save();

        return UserDto::fromModel($user);
    }

    /**
     * @param int $userId
     * @param int $roleId
     * @return UserDto
     */
    public function updateUserRoleById(int $userId, int $roleId): UserDto
    {
        $user = User::find($userId);
        $user->role_id = $roleId;
        $user->save();

        return UserDto::fromModel($user);
    }

    /**
     * @param int $userId
     * @param int $friendId
     * @throws Exception
     */
    public function addFriend(int $userId, int $friendId)
    {
        $user = User::find($userId);
        if (!$user) {
            throw new Exception('User doesn\'nt exist with id' . $userId);
        }

        $friend = User::find($friendId);
        if (!$friend) {
            throw new Exception('User doesn\'nt exist with id' . $friendId);
        }

        $user->addFriend($friendId);

    }

    public function acceptFriend(int $userId, int $friendId)
    {
        $user = User::find($userId);
        if (!$user) {
            throw new Exception('User doesn\'nt exist with id' . $userId);
        }

        $friend = User::find($friendId);
        if (!$friendId) {
            throw new Exception('User doesn\'nt exist with id' . $friendId);
        }

        $user->acceptFriendRequest($friendId);
    }

}
