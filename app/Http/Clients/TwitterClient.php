<?php

declare(strict_types=1);

namespace App\Http\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class TwitterClient implements ClientInterface
{

    public const URL = "https://5fc3ca34e5c28f0016f54de6.mockapi.io/api";

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get(string $endpoint)
    {
        try {
            $response = $this->client->request('GET', self::URL . $endpoint);
            $content = $response->getBody()->getContents();

            return json_decode($content, true);
        } catch (GuzzleException $e) {
            Log::error(sprintf('Can not get [twitter client]: %s', $e->getMessage()));
            throw new \Exception($e->getMessage());
        }
    }
}
