<?php

declare(strict_types=1);


namespace Tests\Integration;


use App\Http\Clients\TwitterClient;
use GuzzleHttp\Client;
use Tests\TestCase;

class ClientTest extends TestCase
{

    /** @test */
    public function it_should_get_100_tweets_from_mock_service()
    {
        $clientService = new TwitterClient(new Client());

        $tweets = $clientService->get('/tweets');

        $this->assertCount(100, $tweets);
    }

    /** @test */
    public function tweets_should_contain_user_id()
    {
        $clientService = new TwitterClient(new Client());
        $tweets = $clientService->get('/tweets');

        $this->assertCount(100, array_column($tweets, 'user_id'));
    }

}
