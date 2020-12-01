<?php

declare(strict_types=1);


namespace Tests\Integration;

use App\Models\Tweet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TweetTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_should_add_tweets_when_user_registered_successfully()
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'johndoe@mail.com',
            'password' => 'pass123',
            'phone' => '0512315123',
            'twitter_address' => 'https://twitter.com/johndoe'
        ];

        $this->post('/api/auth/register', $payload);

        $this->assertDatabaseCount('tweets', 20);
    }

    /** @test */
    public function tweets_should_be_passive_after_user_registered()
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'johndoe@mail.com',
            'password' => 'pass123',
            'phone' => '0512315123',
            'twitter_address' => 'https://twitter.com/johndoe'
        ];

        $this->post('/api/auth/register', $payload);
        $count = Tweet::all()->where('is_published', false)->count();

        $this->assertEquals(20, $count);
    }
}
