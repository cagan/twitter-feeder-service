<?php

declare(strict_types=1);

namespace App\Services\Login;

interface LoginServiceInterface
{

    public function login(array $userData);
}
