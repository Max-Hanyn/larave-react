<?php

namespace App\DTO\User;

use App\Models\User;
use Spatie\DataTransferObject\DataTransferObject;

class UserListDTO extends DataTransferObject
{

    /**
     * @var UserDto []
     */
    public array $userListDto;

    public static function fromArray($users): UserListDTO

    {
        $usersDTO = [];
        $users->each(function ($item, $key) use (&$usersDTO) {
            $usersDTO[] = UserDto::fromModel($item);
        });

        return new static(
            $usersDTO
        );

    }
}
