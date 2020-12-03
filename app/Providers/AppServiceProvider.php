<?php

namespace App\Providers;

use App\Http\Clients\ClientInterface;
use App\Http\Clients\TwitterMockClient;
use App\Repositories\TweetRepository;
use App\Repositories\TweetRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Services\JwtTokenService;
use App\Services\TokenServiceInterface;
use App\Services\TweetService;
use App\Services\TweetServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ClientInterface::class, TwitterMockClient::class);
        $this->app->bind(TweetServiceInterface::class, TweetService::class);
        $this->app->bind(TokenServiceInterface::class, JwtTokenService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TweetRepositoryInterface::class, TweetRepository::class);
    }
}
