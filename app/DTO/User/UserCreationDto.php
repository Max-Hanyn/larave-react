<?php

namespace App\DTO\User;

use App\Http\Requests\Api\Auth\RegisterFormRequest;
use Spatie\DataTransferObject\DataTransferObject;

class UserCreationDto extends DataTransferObject
{
    public string $name;

    public string $email;

    public string $password;

        public static function fromArray(array $data): static
        {
        return new static([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }


}
