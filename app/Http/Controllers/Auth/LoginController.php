<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

class LoginController extends AuthController
{

    public function login(LoginRequest $request)
    {
        if (!$token = auth()->attempt($request->validated())) {
            return response()->json(
                [
                    'status' => 'auth_error',
                    'error' => 'Wrong credentials',
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        if (!auth()->user()->isEmailActivated()) {
            return response()->json(
                [
                    'status' => 'auth_error',
                    'error' => 'User not activated. Please check your email or SMS',
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        return $this->createNewToken((string)$token);
    }
}
