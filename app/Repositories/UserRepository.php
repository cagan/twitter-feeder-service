<?php

declare(strict_types=1);


namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserRepository
{

    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {
        try {
            return $this->model->create($attributes);
        } catch (\Exception $e) {
            Log::error(sprintf("Can not create user: %s", $e->getMessage()));
            throw new \Exception($e->getMessage());
        }
    }

}
