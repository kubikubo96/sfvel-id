<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseApiController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get a JWT via given credentials.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only(['email', 'password']);
            if (! $token = auth()->attempt($credentials)) {
                return $this->responseErrors(401, 'Sai tài khoản hoặc mật khẩu.');
            }

            $user = auth()->user();
            $response = [
                'token' => $token,
                'expires_in' => auth()->payload()->toArray()['exp'],
                'user' => UserResource::make($user)->resolve(),
            ];

            return $this->responseSuccess($response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseErrors(500, $e->getMessage());
        }
    }

    /**
     * Get the authenticated User.
     */
    public function me(): JsonResponse
    {
        $me = auth()->user();
        if ($me) {
            return $this->responseSuccess(UserResource::make($me)->resolve());
        } else {
            return $this->responseForbiddenRequest();
        }
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     */
    public function refresh(): JsonResponse
    {
        return $this->responseSuccess(auth()->refresh());
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = auth()->user();
        $data = $request->only([
            'old_password',
            'new_password',
            're_new_password',
        ]);
        try {
            if (! Hash::check($data['old_password'], $user->password)) {
                return $this->responseErrors(400, 'Mật khẩu cũ không trùng khớp.');
            }
            if ($data['new_password'] == $data['old_password']) {
                return $this->responseErrors(400, 'Mật khẩu mới trùng với mật khẩu cũ.');
            }
            $user->password = Hash::make($data['new_password']);
            $user->save();

            return $this->responseSuccess(UserResource::make($user)->resolve());
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalServerError($e->getMessage());
        }
    }
}
