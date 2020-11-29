<?php

declare(strict_types=1);


namespace App\Http\Controllers\Auth;


class LogoutController extends AuthController
{

    public function logout()
    {
        auth()->logout();

        return response()->json(
            [
                'message' => 'User successfully signed out',
            ],
            200
        );
    }

}
