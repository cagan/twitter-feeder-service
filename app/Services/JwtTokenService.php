<?php

declare(strict_types=1);


namespace App\Services;

class JwtTokenService implements TokenServiceInterface
{

    public function refreshToken()
    {
        if (!auth()) {
            return null;
        }

        return auth()->refresh();
    }

    public function createToken(int $expireMin = 60)
    {
        if (!auth()) {
            return null;
        }

        return auth()->factory()->getTTL() * $expireMin;
    }
}
