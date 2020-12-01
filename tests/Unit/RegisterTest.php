<?php

declare(strict_types=1);


namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    use RefreshDatabase;

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->payload = [
            'name' => 'John Doe',
            'email' => 'johndoe@mail.com',
            'password' => 'pass123',
            'phone' => '0512315123',
            'twitter_address' => 'https://twitter.com/johndoe',
        ];
    }

    /** @test */
    public function it_should_register_user_successfully()
    {
        Notification::fake();

        $this->assertDatabaseCount('users', 0);

        $this->post('/api/auth/register', $this->payload);

        $this->assertDatabaseCount('users', 1);
    }

    /** @test */
    public function it_should_validate_input_before_registering_user()
    {
        Notification::fake();

        unset($this->payload['email']);

        $this->assertDatabaseCount('users', 0);

        $response = $this->post('/api/auth/register', $this->payload);

        $this->assertTrue(strpos($response->getContent(), 'validation error') > -1);

        $this->assertDatabaseCount('users', 0);
    }

    /** @test */
    public function it_should_not_send_notification_when_user_not_created()
    {
        Notification::fake();
        $user = User::factory(['twitter_address' => 'https://twitter.com/johndoe'])->create();
        $this->post('/api/auth/register', $user->getAttributes());

        Notification::assertNothingSent();
    }

}
