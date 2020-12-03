<?php

declare(strict_types=1);


namespace App\Repositories;

interface UserRepositoryInterface extends RepositoryInterface
{

    public function create(array $values);

    public function findById(int $id);
}
