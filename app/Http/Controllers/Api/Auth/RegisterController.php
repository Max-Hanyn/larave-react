<?php

namespace App\Http\Controllers\Api\Auth;

use App\DTO\User\UserCreationDto;
use App\Events\NewMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterFormRequest;
use App\Services\Api\UserService;
use Illuminate\Support\Facades\Auth;


class RegisterController extends Controller
{

    public function register(RegisterFormRequest $request, UserService $userService)
    {

        $userCreationDto = UserCreationDto::fromArray($request->validated());

        $user = $userService->create($userCreationDto);

        $accessToken = $userService->createToken($user);

//        NewMessage::dispatch('gfdgdf');

        return response()->json(['accessToken' => $accessToken], 200);

    }

}
