<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTweetRequest;
use App\Http\Resources\TweetCollection;
use App\Http\Resources\TweetResource;
use App\Models\Tweet;
use App\Services\Tweet\TweetServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TweetController extends Controller
{

    private TweetServiceInterface $tweetService;

    public function __construct(
        TweetServiceInterface $tweetService
    ) {
        $this->middleware('auth:api');
        $this->tweetService = $tweetService;
    }

    public function index(Request $request)
    {
        $userId = $request->query('userId');
        $page = $request->query('page') ?: 1;

        if (!$userId) {
            $tweets = $this->tweetService->getAllTweets($page);

            return new TweetCollection($tweets);
        }

        try {
            $tweets = $this->tweetService->getAllTweetsByUserId($userId, $page);

            return new TweetCollection($tweets);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]
            );
        }
    }

    public function show(int $tweetId)
    {
        try {
            $tweet = $this->tweetService->getTweetById($tweetId);

            return new TweetResource($tweet);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Tweet not found with this id.',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }

    public function update(Tweet $tweet, UpdateTweetRequest $request)
    {
        $validated = $request->validated();
        $this->authorize('update', $tweet);

        $updated = $this->tweetService->updateTweet($tweet->id, $validated);

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

    public function publish(Tweet $tweet, TweetServiceInterface $tweetService)
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
