<?php

namespace App\Providers;

use App\Repositories\implementation\TaskRepository;
use App\Repositories\implementation\UserRepository;
use App\Repositories\ITaskRepository;
use App\Repositories\IUserRepository;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(ITaskRepository::class, TaskRepository::class);
        $this->app->singleton(Client::class, fn() => new Client());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
