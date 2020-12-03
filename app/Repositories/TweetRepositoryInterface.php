<?php


namespace App\Repositories;

interface TweetRepositoryInterface extends RepositoryInterface
{

    public function getAllTweets(int $limit = 10, string $order = 'DESC');

    public function getTweetByUserId(int $userId, string $oder = 'DESC', int $limit = 10);

    public function update(int $tweetId, array $data);

    public function create(array $data);

    public function publish(int $tweetId);

}
