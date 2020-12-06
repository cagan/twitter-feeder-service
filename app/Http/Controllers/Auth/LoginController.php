<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\DeviceCountExceedException;
use App\Exceptions\WrongCredentialsException;
use App\Http\Requests\LoginRequest;
use App\Services\Login\LoginServiceInterface;
use App\Services\TokenServiceInterface;
use Illuminate\Http\JsonResponse;

class LoginController extends AuthController
{

    private LoginServiceInterface $jwtLogin;

    public function __construct(TokenServiceInterface $tokenService, LoginServiceInterface $jwtLogin)
    {
        parent::__construct($tokenService);
        $this->jwtLogin = $jwtLogin;
    }

    public function login(LoginRequest $request)
    {
        try {
            $token = $this->jwtLogin->login($request->validated());

            return $this->newTokenResponse($token);
        } catch (DeviceCountExceedException $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (WrongCredentialsException $e) {
            return response()->json(
                [
                    'status' => 'auth_error',
                    'message' => $e->getMessage(),
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }
    }

}
