<?php

declare(strict_types=1);

namespace App\Services\Tweet;

use App\Http\Clients\ClientInterface;
use App\Repositories\TweetRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

class TweetService implements TweetServiceInterface
{

    private ClientInterface $client;

    private TweetRepositoryInterface $tweetRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        ClientInterface $client,
        TweetRepositoryInterface $tweetRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->client = $client;
        $this->tweetRepository = $tweetRepository;
        $this->userRepository = $userRepository;
    }

    public function getAllTweets(int $page = 1)
    {
        // If the user doesn't enters userId, we will show all the tweets in the system.
        return \Cache::remember(
            "tweet.all.page:{$page}",
            33600,
            function () {
                return $this->tweetRepository->getAllTweets();
            }
        );
    }

    public function getAllTweetsByUserId(int $userId = 1, int $page = 1)
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new \Exception('User not found with that userId');
        }

        return \Cache::remember(
            "tweet.user:{$userId}:page:{$page}",
            33600,
            function () use ($userId) {
                return $this->tweetRepository->getTweetByUserId($userId);
            }
        );
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

    public function getTweetById(int $tweetId)
    {
        $tweet = \Cache::remember(
            "tweet.id:{$tweetId}",
            33600,
            function () use ($tweetId) {
                return $this->tweetRepository->findById($tweetId);
            }
        );

        if (!$tweet) {
            throw new \Exception('Tweet not found with this id');
        }

        return $tweet;
    }

    public function updateTweet(int $tweetId, array $values)
    {
        \Cache::clear();
        return $this->tweetRepository->update($tweetId, $values);
    }
}
