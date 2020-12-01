<?php

declare(strict_types=1);


namespace Tests\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JwtTokenTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_should_return_token_when_user_logged_in()
    {
        $user = User::factory(['email_active' => true, 'twitter_address' => 'https://twitter.com/johndoe'])->create();

        $response = $this->json(
            'POST',
            '/api/auth/login',
            [
                'email' => $user->email,
                'password' => 'password',
            ]
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token', 'user', 'token_type']);
    }

    /** @test */
    public function it_should_return_error_when_user_tries_to_login_without_activation()
    {
        $user = User::factory(['email_active' => false, 'twitter_address' => 'https://twitter.com/johndoe'])->create();

        $response = $this->json(
            'POST',
            '/api/auth/login',
            [
                'email' => $user->email,
                'password' => 'password',
            ]
        );


        $response->assertStatus(401);
        $response->assertJson(
            ['status' => 'auth_error', 'error' => 'User not activated. Please check your email or SMS']
        );
    }
}
