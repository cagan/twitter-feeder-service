<?php

declare(strict_types=1);


namespace App\Repositories;


use App\Models\Tweet;

class TweetRepository
{

    protected Tweet $tweet;

    public function __construct(Tweet $tweet)
    {
        $this->tweet = $tweet;
    }

    public function getAllTweets(int $limit = 10, string $order = 'DESC')
    {
        return $this->tweet->orderBy('created_at', $order)->paginate($limit);
    }

    public function getTweetByUserId(int $userId, string $order = 'DESC', int $limit = 10)
    {
        return $this->tweet->where('user_id', $userId)->orderBy('created_at', $order)->paginate($limit);
    }

    public function findById(int $tweetId)
    {
        return $this->tweet->where('id', $tweetId)->first();
    }

}
