<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Post\PostController;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\Api\User\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');



Route::get('/user/{id?}', [UserController::class, 'getUser'])->middleware('auth:api')->where(['id' => '[0-9]+']);
Route::put('/user/{id?}', [UserController::class, 'updateUser'])->middleware('auth:api')->where(['id' => '[0-9]+']);
Route::put('/user/role', [UserController::class, 'updateUserRole'])->middleware('auth:api');
Route::get('/user/list', [UserController::class, 'getAllUsers'])->middleware('auth:api');



Route::get('/role/{id}', [RoleController::class, 'getRole'])->middleware('auth:api')->where(['id' => '[0-9]+']);
Route::post('/role', [RoleController::class, 'addRole'])->middleware('auth:api');
Route::put('/role', [RoleController::class, 'updateRole'])->middleware('auth:api');
Route::delete('/role', [RoleController::class, 'removeRole'])->middleware('auth:api');
Route::get('/role/list', [RoleController::class, 'getAllRoles'])->middleware('auth:api');

Route::post('/user/friends/{id}', [UserController::class, 'addUserFriend'])->middleware('auth:api')->where(['id' => '[0-9]+']);
Route::put('/user/friends/accept/{id}', [UserController::class, 'acceptUserFriend'])->middleware('auth:api')->where(['id' => '[0-9]+']);
Route::post('/user/friends/delete/{id}', [UserController::class, 'deleteUserFriend'])->middleware('auth:api')->where(['id' => '[0-9]+']);
Route::post('/user/friends/block/{id}', [UserController::class, 'blockUserFriend'])->middleware('auth:api')->where(['id' => '[0-9]+']);


Route::post('/posts', [PostController::class, 'createPost'])->middleware('auth:api');
Route::get('/posts/{id}', [PostController::class, 'getPost'])->middleware('auth:api')->where(['id' => '[0-9]+']);
Route::put('/posts', [PostController::class, 'updatePost'])->middleware('auth:api');
Route::delete('/posts/{id}', [PostController::class, 'deletePost'])->middleware('auth:api')->where(['id' => '[0-9]+']);
Route::get('/posts/user/{userId?}', [PostController::class, 'getUserPosts'])->middleware('auth:api')->where(['userId' => '[0-9]+']);
Route::get('/posts/list', [PostController::class, 'getPostsByPage'])->middleware('auth:api');








