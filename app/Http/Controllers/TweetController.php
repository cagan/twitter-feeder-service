<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\TweetRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TweetController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request, TweetRepository $repository)
    {
        $userId = $request->query('userId');

        if (!$userId) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'userId parameter required',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $user = User::where('id', $userId)->first();

        if (!$user) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'User not found with that userId',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $feed = $repository->getByUserId($user->id);

        return response()->json(
            [
                'status' => 'success',
                'data' => [
                    $feed
                ]
            ],
            JsonResponse::HTTP_OK
        );
    }

    public function show($tweetId, TweetRepository $repository)
    {
        $feed = $repository->findById($tweetId);

        if (!$feed) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Feed not found with this id.'
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'data' => $feed
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
