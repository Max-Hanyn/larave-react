<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterFormRequest;
use App\Models\User;
use App\Services\Api\Roles;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function register(RegisterFormRequest $request, Roles $roles){

        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $roles->getRoleIdBySlug(Roles::MODERATOR_ROLE_SLUG),
        ]);

        $token = $user->createToken('api')->accessToken;

        return response()->json(['accessToken' => $token], 201);

    }

    public function user()
    {
        return response()->json(['user' => Auth::user()]);
    }
}
