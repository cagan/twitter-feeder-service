<?php

declare(strict_types=1);

namespace App\Http\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class TwitterMockClient implements ClientInterface
{
    // This is a public url so no need to use .env file
    public const MOCK_API_URL = "https://5fc3ca34e5c28f0016f54de6.mockapi.io/api";

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetchData(string $endpoint)
    {
        try {
            $response = $this->client->request('GET', self::MOCK_API_URL . $endpoint);
            $content = $response->getBody()->getContents();

            return json_decode($content, true);
        } catch (GuzzleException $e) {
            Log::error(sprintf('Can not get [twitter client]: %s', $e->getMessage()));
            throw new \Exception($e->getMessage());
        }
    }
}
