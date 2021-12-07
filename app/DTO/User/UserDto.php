<?php

namespace App\DTO\User;

use App\DTO\Role\RoleDto;
use App\Models\User;
use Spatie\DataTransferObject\DataTransferObject;

class UserDto extends DataTransferObject
{
    public int $id;

    public string $name;

    public string $email;

    public RoleDto $role;

    public static function fromModel(User $user): static
    {
        return new static([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => [
                'id' => $user->role->id,
                'name' => $user->role->name,
                'slug' => $user->role->slug,
            ]
        ]);
    }


}
