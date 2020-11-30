<?php

namespace App\Http\Controllers;

use App\Http\Resources\TweetCollection;
use App\Repositories\TweetRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TweetController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request, TweetRepository $tweetRepository, UserRepository $userRepository)
    {
        $userId = $request->query('userId');

        // If the user doesn't enters userId, we will show all the tweets in the system.
        if (!$userId) {
            $tweets = $tweetRepository->getAllTweets();

            return new TweetCollection($tweets);
        }

        $user = $userRepository->findBy($userId);

        if (!$user) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'User not found with that userId',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $tweets = $tweetRepository->getTweetByUserId($user->id);

        return new TweetCollection($tweets);
    }

    public function show($tweetId, TweetRepository $repository)
    {
        $feed = $repository->findById($tweetId);

        if (!$feed) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Feed not found with this id.',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'data' => $feed,
            ]
        );
    }

    public function publish($tweetId)
    {
        dd(auth()->user()->id);
        /**
         * @TODO Publish user tweets with policy check.
         */
    }

}
