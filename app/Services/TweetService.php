<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Clients\ClientInterface;
use App\Repositories\TweetRepositoryInterface;
use Illuminate\Support\Facades\Log;

class TweetService implements TweetServiceInterface
{

    private ClientInterface $client;

    private TweetRepositoryInterface $tweetRepository;

    public function __construct(ClientInterface $client, TweetRepositoryInterface $tweetRepository)
    {
        $this->client = $client;
        $this->tweetRepository = $tweetRepository;
    }

    public function loadTweets(int $userId, int $amount): void
    {
        try {
            $tweets = $this->client->fetchData('/tweets');

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

            $this->tweetRepository->create(array_values($userTweets));
            Log::info('Registered users tweets are saved to database.');
        } catch (\Exception $e) {
            throw new \Exception(sprintf("Can not load tweets: %s", $e->getMessage()));
        }
    }

    public function publishTweet(int $tweetId): int
    {
        return $this->tweetRepository->publish($tweetId);
    }
}
