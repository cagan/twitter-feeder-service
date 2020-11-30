<?php

declare(strict_types=1);


namespace App\Services;


use App\Http\Clients\ClientInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TweetService
{

    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function loadTweets(int $userId, int $amount)
    {
        try {
            $tweets = $this->client->get('/tweets');

            $userTweets = array_filter(
                $tweets,
                function ($tweet) use ($userId) {
                    return $tweet['user_id'] === $userId;
                }
            );
            $userTweets = array_slice($userTweets, 0, $amount);

            foreach ($userTweets as &$tweet) {
                $date = date($tweet['created_at']);
                $tweet['created_at'] = new \DateTime($date);
            }

            DB::table('tweets')->insert(array_values($userTweets));

            Log::info('Registered users tweets are saved to database.');
        } catch (\Exception $e) {
            throw new \Exception(sprintf("Can not load tweets: %s", $e->getMessage()));
        }
    }

    public function publishTweet()
    {

    }
}
