<?php

declare(strict_types=1);


namespace Tests\Integration;


use App\Models\ActivationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivationTokenTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_should_activate_user_when_right_activation_code_entered()
    {
        $user = User::factory(['email_active' => false, 'twitter_address' => 'https://twitter.com/johndoe'])->create();
        $activationCode = 'ABC12';

        $activationCode = ActivationCode::create(
            [
                'user_id' => $user->id,
                'activation_code' => $activationCode,
                'is_activated' => false,
            ]
        );

        $this->assertFalse($activationCode->is_activated);

        $response = $this->json(
            'POST',
            "/api/auth/register/activate/{$user->id}/$user->activation_token",
            [
                'activation_code' => $activationCode->activation_code,
            ]
        );

        $response->assertStatus(200);

        $activated = ActivationCode::first()->is_activated ? true : false;

        $this->assertTrue($activated);
    }

    /** @test */
    public function it_should_response_error_when_activation_code_already_activated()
    {
        $user = User::factory(['email_active' => false, 'twitter_address' => 'https://twitter.com/johndoe'])->create();
        $activationCode = 'ABC12';

        $activationCode = ActivationCode::create(
            [
                'user_id' => $user->id,
                'activation_code' => $activationCode,
                'is_activated' => true,
            ]
        );

        $response = $this->json(
            'POST',
            "/api/auth/register/activate/{$user->id}/$user->activation_token",
            [
                'activation_code' => $activationCode->activation_code,
            ]
        );

        $response->assertStatus(422);

        $response->assertJson(
            [
                'status' => 'error',
                'message' => 'Can not verify account with activation code',
            ]
        );
    }

}
