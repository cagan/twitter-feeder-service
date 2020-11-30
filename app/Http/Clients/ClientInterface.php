<?php

declare(strict_types=1);


namespace App\Http\Clients;


interface ClientInterface
{

    public function get(string $endpoint);

}
