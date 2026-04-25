<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GreetingServiceInterface;
use App\Services\EnglishGreetingService;

class GreetingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the interface to the implementation in the Service Container
        $this->app->bind(GreetingServiceInterface::class, EnglishGreetingService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
