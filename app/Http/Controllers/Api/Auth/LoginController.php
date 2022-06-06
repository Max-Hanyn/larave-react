<?php

namespace App\Http\Controllers\Api\Auth;

use App\DTO\User\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
      /**
       * Login user
       * @OA\Post (
       *     path="/api/login",
       *     operationId = "LoginUser",
       *     tags={"Auth"},
       *     @OA\RequestBody(
       *     required=true,
       *         @OA\MediaType(
       *             mediaType="application/json",
       *             @OA\Schema(
       *                  @OA\Property(
       *                      property="email",
       *                      description="Add email",
       *                      type="string",
       *                   ),
       *                  @OA\Property(
       *                      property="password",
       *                      description="Add password",
       *                      type="string",
       *                   ),
       *             )
       *
       *         ),
       *
       *      ),
       *      @OA\Response(
       *          response=200,
       *          description="success",
       *          @OA\JsonContent(
       *              @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNTk3YzM3ZGEzMmIxNDQ0YzYxMmFhNTNhNjY4NWMyNGY0NTZiY2VkMWVlZTI5ODU0MTAzOTY2MTVhNWEyOGJhYWY1NTQzNTllMThhNDZmZWIiLCJpYXQiOjE2NTMzODUwNTkuNDk4NDExLCJuYmYiOjE2NTMzODUwNTkuNDk4NDE2LCJleHAiOjE2ODQ5MjEwNTkuMzU1MjEzLCJzdWIiOiIxNiIsInNjb3BlcyI6W119.aTmcL26LpTa18KQkWLjTfuyhI9eVjoxsK5g7l7xh3Ia4dtYS0WWk-1-_IfaO-TkxhNOROozoVqhV3TYYBDriYdXmFz2DExPVvlVbfgb4tTborAYTDHeMNw3fCA0nbrIS6HxG6XEAhYUDcND4YVlrmx6kAMSCm4PCdYbJHLepXDBIrYjRRTaSoe5aDM5Xpm_HE4XykxUMqzyaiNZLkCKWqCx9Ezck_aM3Tk7CWuh-HZgZ2sJ78GES6RW1osKfszm9hxaIFhnmltzhPFR2VRqLg8BV4lFJVcJJ2iLnbcSzx-KEZnDp-w7-JekjNa1YYA0CnuekGVj4-VToKfTsRjMat2O0ClPWptq1SGkKwP5wgg_P3DNceMleCiC2uRp3XB_ImVhXbujYOwk1Ag7oQto-OOVMslAMkR8u94jek8VDgW1VE1nVBsDby5zDGinPb3GcE5JlFXXLyKv5j39JtBh-xiC4xCH10dsBYGX2N0WTzz2as1a1MWxQ4K9soGYOV0DV7KALMM_tEUn0-_1ZWu3be602Y0y0UPIP9L6NdLaJLC8K0_f0ZbTdSWRtvmDPeLLpTOYisvBtyt560vO3AApNl6xvnKAuS6w0o1Flu4JJF78teg_KpnS3RsKSREYkuQtQplpJqbkck5Xb4FAd-tVT3d7a0-ollMtkVEbdIUsrS4k"),
       *              @OA\Property(property="token_type", type="string", example="Bearer"),
       *              @OA\Property(property="expires_at", type="string", example="2022-05-25 10:24:21"),
       *              @OA\Property(property="User", type="object",
       *                      @OA\Property(
       *                         property="id",
       *                         type="number",
       *                         example="1"
       *                      ),
       *                      @OA\Property(
       *                         property="name",
       *                         type="string",
       *                         example="Max"
       *                      ),
       *                      @OA\Property(
       *                         property="email",
       *                         type="string",
       *                         example="test@test.test"
       *                      ),
       *                      @OA\Property(
       *                         property="role",
       *                         type="object",
       *                                  @OA\Property(
       *                                      property="id",
       *                                      type="number",
       *                                      example="1"
       *                                  ),
       *                                  @OA\Property(
       *                                      property="name",
       *                                      type="string",
       *                                      example="User"
       *                                  ),
       *                                  @OA\Property(
       *                                      property="email",
       *                                      type="string",
       *                                      example="user"
       *                                  ),
       *                        ),
       *             ),
       *          )
       *      ),
       *      @OA\Response(
       *          response=400,
       *          description="invalid",
       *          @OA\JsonContent(
       *              @OA\Property(property="msg", type="string", example="fail"),
       *          )
       *      )
       * )
       */

    public function login(LoginFormRequest $request)
    {

        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'You cannot sign with those credentials',
                'errors' => 'Unauthorised'
            ], 401);
        }

        $token = Auth::user()->createToken(config('app.name'));

        $token->token->expires_at = $request->remember_me ?
            Carbon::now()->addMonth() :
            Carbon::now()->addDay();

        $token->token->save();

        $user = UserDto::fromModel(User::find(Auth::id()));

        return response()->json([
            'token_type' => 'Bearer',
            'token' => $token->accessToken,
            'user' => $user,
            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
        ], 200);

    }


    /**
     * Logout user
     * @OA\Post (
     *     path="/api/logout",
     *     operationId = "LogoutUser",
     *     tags={"Auth"},
     *     security={
     *         {"passport": {}},
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", type="string", example="true"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    public function logout(Request $request)
    {

        return response()->json(['user' => Auth::user()->token()->revoke()]);
    }
}
