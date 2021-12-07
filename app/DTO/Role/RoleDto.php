<?php

namespace App\DTO\Role;

use App\Models\Roles;
use Spatie\DataTransferObject\DataTransferObject;

class RoleDto extends DataTransferObject
{
    public int $id;

    public string $name;

    public string $slug;

    public static function fromModel(Roles $role): static
    {
        return new static([
            'id' => $role->id,
            'name' => $role->name,
            'slug' => $role->slug,
        ]);
    }

}
