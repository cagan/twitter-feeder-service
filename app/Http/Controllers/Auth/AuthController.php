<?php

declare(strict_types=1);


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;

abstract class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'signed', 'auth:api'], ['except' => ['login', 'register', 'verifyAccount']]);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    protected function createNewToken(string $token)
    {
        return response()->json(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user(),
            ]
        );
    }

}
