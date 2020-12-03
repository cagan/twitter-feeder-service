<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTweetRequest;
use App\Http\Resources\TweetCollection;
use App\Http\Resources\TweetResource;
use App\Models\Tweet;
use App\Repositories\TweetRepository;
use App\Repositories\TweetRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\TweetService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TweetController extends Controller
{

    private TweetRepositoryInterface $tweetRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(TweetRepositoryInterface $tweetRepository, UserRepositoryInterface $userRepository)
    {
        $this->middleware('auth:api');
        $this->tweetRepository = $tweetRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $userId = $request->query('userId');

        // If the user doesn't enters userId, we will show all the tweets in the system.
        if (!$userId) {
            $tweets = $this->tweetRepository->getAllTweets();

            return new TweetCollection($tweets);
        }

        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'User not found with that userId',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $tweets = $this->tweetRepository->getTweetByUserId($user->id);

        return new TweetCollection($tweets);
    }

    public function show(int $tweetId, TweetRepository $repository)
    {
        $tweet = $repository->findById($tweetId);

        if (!$tweet) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Tweet not found with this id.',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        return new TweetResource($tweet);
    }

    public function update(Tweet $tweet, UpdateTweetRequest $request, TweetRepository $repository)
    {
        $validated = $request->validated();
        $this->authorize('update', $tweet);

        $updated = $repository->update($tweet->id, $validated);

        if ($updated) {
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Tweet updated successfully',
                    'data' => [
                        'tweet_id' => $tweet->id,
                    ],
                ],
                JsonResponse::HTTP_OK
            );
        }

        return response()->json(
            [
                'status' => 'error',
                'message' => 'Something wrong with API.',
            ],
            JsonResponse::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public function publish(Tweet $tweet, TweetService $tweetService)
    {
        $this->authorize('update', $tweet);
        $isPublished = $tweetService->publishTweet($tweet->id);

        if ($isPublished) {
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Tweet now published in server',
                ],
                JsonResponse::HTTP_OK
            );
        }

        return response()->json(
            [
                'status' => 'error',
                'message' => 'Can not publish in the server',
            ],
            JsonResponse::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
