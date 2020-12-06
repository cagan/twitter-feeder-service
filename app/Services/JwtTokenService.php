<?php

declare(strict_types=1);


namespace App\Services;

use App\Contracts\Storable;
use App\Models\Authorization;

class JwtTokenService implements TokenServiceInterface, Storable
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

    public function store(array $data)
    {
        $userId = $data['user_id'] ?: auth()->user()->id;

        $numberOfDevices = $this->getNumberOfDevices($userId);

        if ($numberOfDevices > 0) {
            return Authorization::where('user_id', $userId)->update(
                [
                    'device_count' => ++$numberOfDevices,
                ]
            );
        }

        return Authorization::create(
            [
                'user_id' => $userId,
                'jwt_token' => $data['token'],
                'expire_time' => date('Y-m-d H:i:s', $data['expire_time']),
            ]
        );
    }

    public function getNumberOfDevices(int $userId)
    {

        $device = Authorization::select('device_count')->where('user_id', $userId)->first();

        return $device ? $device->device_count : 0;
    }
}
