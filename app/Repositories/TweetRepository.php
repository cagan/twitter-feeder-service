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

    public function getByUserId(int $userId)
    {
        return $this->tweet->where('user_id', $userId)->get();
    }

    public function findById(int $tweetId)
    {
        return $this->tweet->where('id', $tweetId)->first();
    }

}
