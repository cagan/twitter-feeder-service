<?php

declare(strict_types=1);


namespace App\Services;


use App\Models\ActivationCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ActivationCodeService
{

    private ActivationCode $activationCode;

    public function __construct(ActivationCode $activationCode)
    {
        $this->activationCode = $activationCode;
    }

    public function generate(User $user)
    {
        $code = strtoupper(Str::random(5));

        $this->activationCode::create(
            [
                'activation_code' => $code,
                'user_id' => $user->id,
            ]
        );

        return $code;
    }

    public function validate(string $userId, string $activationCode)
    {
        return $this->activationCode
            ->where('activation_code', $activationCode)
            ->where('user_id', $userId)
            ->where('is_activated', 0)
            ->exists();
    }

    public function activate(string $userId, string $activationCode)
    {
        $valid = $this->validate($userId, $activationCode);
        if (!$valid) {
            return false;
        }

        $userActivationCode = $this->activationCode->where('activation_code', $activationCode)->first();
        $user = User::where('id', $userId)->where('email_active', 0)->first();

        if (!$user) {
            return false;
        }

        try {
            DB::beginTransaction();

            $user->update(
                [
                    'email_active' => 1,
                ]
            );

            $userActivationCode->update(
                [
                    'is_activated' => 1,
                ]
            );

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error(sprintf("Can not activate user: %s", $e->getMessage()));
            return false;
        }
    }

}
