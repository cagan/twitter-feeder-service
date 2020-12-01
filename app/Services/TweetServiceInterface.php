<?php

declare(strict_types=1);


namespace App\Services;

interface TweetServiceInterface
{

    public function loadTweets(int $userId, int $amount);

    public function publishTweet(int $tweetId);
}
