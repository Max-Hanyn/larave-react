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
//            dd(UserDto::fromModel($item));
            $usersDTO[] = UserDto::fromModel($item);
        });

        dd($usersDTO);

        return new static(

            $usersDTO

        );

    }
}
