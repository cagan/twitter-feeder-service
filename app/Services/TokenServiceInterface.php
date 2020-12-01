<?php

declare(strict_types=1);


namespace App\Services;

interface TokenServiceInterface
{

    public function refreshToken();

    public function createToken(int $expireMin = 60);

}
