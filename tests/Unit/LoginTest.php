<?php

declare(strict_types=1);


namespace Tests\Unit;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_should_login_successfully()
    {
        $user = User::factory(['twitter_address' => 'https://twitter.com/johndoe', 'email_active' => true])->create();

        $email = $user->getAttribute('email');
        $password=  'password';

        $response = $this->post('/api/auth/login', ['email' => $email, 'password' => $password]);

        $response->assertJsonStructure(['access_token', 'user']);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_validate_email()
    {
        $email = 'wrong@email.com';
        $password=  'password';

        $response = $this->post('/api/auth/login', ['email' => $email, 'password' => $password]);
        $response->assertStatus(401);

        $response->assertJsonStructure(['status', 'error']);
    }

    /** @test */
    public function it_should_validate_password()
    {
        $user = User::factory(['twitter_address' => 'https://twitter.com/johndoe', 'email_active' => true])->create();
        $email = $user->getAttribute('email');
        $password=  'wrongpass';

        $response = $this->post('/api/auth/login', ['email' => $email, 'password' => $password]);
        $response->assertStatus(401);

        $response->assertJsonStructure(['status', 'error']);
    }
}
