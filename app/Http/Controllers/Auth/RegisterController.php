<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterVerifyRequest;
use App\Notifications\ActivateSignup;
use App\Repositories\UserRepositoryInterface;
use App\Services\ActivationCodeService;
use App\Services\TokenServiceInterface;
use App\Services\TweetServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterController extends AuthController
{

    private ActivationCodeService $activationCodeService;

    private TweetServiceInterface $tweetService;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        ActivationCodeService $activationCode,
        TweetServiceInterface $tweetService,
        TokenServiceInterface $tokenService,
        UserRepositoryInterface $userRepository
    ) {
        parent::__construct($tokenService);
        $this->activationCodeService = $activationCode;
        $this->tweetService = $tweetService;
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = $this->userRepository->create(
                array_merge(
                    $request->validated(),
                    [
                        'password' => bcrypt($request->get('password')),
                        'activation_token' => md5(rand(1, 10) . microtime()),
                    ]
                )
            );

            if (null !== $user) {
                $activationCode = $this->activationCodeService->generate($user);
                $user->notify(new ActivateSignup($user, $activationCode));
                $this->tweetService->loadTweets($user->id, 20);
            }

            DB::commit();

            return response()->json(
                [
                    'message' => 'Activation code sent. Please validate your email.',
                    'user' => $user,
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Something went wrong, please try again later',
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function verifyAccount($userId, RegisterVerifyRequest $request)
    {
        $activationCode = $request->get('activation_code');
        $activated = $this->activationCodeService->activate($userId, $activationCode);

        if ($activated) {
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Your account has been activated, you can login',
                ],
                JsonResponse::HTTP_OK
            );
        }

        return response()->json(
            [
                'status' => 'error',
                'message' => 'Can not verify account with activation code',
            ],
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function refreshToken()
    {
        return parent::refresh();
    }
}
