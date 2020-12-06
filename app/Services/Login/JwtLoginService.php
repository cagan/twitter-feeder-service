<?php

declare(strict_types=1);


namespace App\Services\Login;

use App\Exceptions\DeviceCountExceedException;
use App\Exceptions\WrongCredentialsException;
use App\Models\Authorization;
use App\Models\User;
use App\Services\TokenServiceInterface;

class JwtLoginService implements LoginServiceInterface
{

    private TokenServiceInterface $tokenService;

    private Authorization $authorization;

    private const NUMBER_OF_DEVICE = 5;

    public function __construct(TokenServiceInterface $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function login(array $userData)
    {
        if (!$userData['email']) {
            throw new \Exception('email field required');
        }

        $userId = User::select('id')->where('email', $userData['email'])->first()->id;
        $numberOfDevices = $this->tokenService->getNumberOfDevices($userId);

        if ($numberOfDevices >= self::NUMBER_OF_DEVICE) {
            throw new DeviceCountExceedException('Number of device exceeded');
        }

        if ($token = $this->getUserToken($userId)) {
            auth()->setToken($token)->user();

            return $token;
        }

        if (!$token = auth()->attempt($userData)) {
            throw new WrongCredentialsException('Wrong credentials');
        }

        $this->tokenService->store(
            ['token' => $token, 'user_id' => auth()->user()->id, 'expire_time' => auth()->payload()->get('exp')]
        );

        return $token;
    }

    private function getExpireTime($userId)
    {
        $expireTime = Authorization::select('expire_time')->where('user_id', $userId)->first();

        return $expireTime ? strtotime($expireTime->expire_time) : null;
    }

    private function getUserToken($userId)
    {
        $expireTime = $this->getExpireTime($userId);

        if ($expireTime && $expireTime > time()) {
            if ($token = \Cache::get("users:{$userId}:jwt_token")) {
                return $token;
            }
            $token = Authorization::where('user_id', $userId)->first()->jwt_token;
            \Cache::put("users:{$userId}:jwt_token", $token, ($expireTime - time()));

            return $token;
        }

        return null;
    }
}
