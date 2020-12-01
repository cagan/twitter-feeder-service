<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TokenServiceInterface;

abstract class AuthController extends Controller
{

    protected TokenServiceInterface $tokenService;

    public function __construct(TokenServiceInterface $tokenService)
    {
        $this->middleware(['auth:api'], ['except' => ['login', 'register', 'verifyAccount']]);
        $this->tokenService = $tokenService;
    }

    protected function newTokenResponse(string $token)
    {
        return response()->json(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->tokenService->createToken(),
                'user' => auth()->user(),
            ]
        );
    }

    public function refresh()
    {
        $token = $this->tokenService->refreshToken();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Token refreshed successfully',
                'token' => $token
            ]
        );
    }
}
