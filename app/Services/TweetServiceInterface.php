<?php

declare(strict_types=1);


namespace App\Services;

interface TweetServiceInterface
{

    public function getTweetById(int $tweetId);

    public function getAllTweets(int $page);

    public function updateTweet(int $tweetId, array $values);

    public function getAllTweetsByUserId(int $userId, int $page);

    public function loadTweets(int $userId, int $amount);

    public function publishTweet(int $tweetId);
}
