<?php

declare(strict_types=1);


namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterService implements RegisterServiceInterface
{

    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function register(array $values)
    {
        try {
            DB::beginTransaction();

            $user = $this->userRepository->create(
                array_merge(
                    $values,
                    [
                        'password' => bcrypt($values['password']),
                        'activation_token' => md5(rand(1, 10) . microtime()),
                    ]
                )
            );

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Can not register new user');
        }
    }

}
