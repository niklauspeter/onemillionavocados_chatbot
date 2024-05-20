<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;
use BotMan\BotMan\BotManFactory;

class BotManServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register BotMan
        $this->app->singleton('botman', function ($app) {
            // Load the Facebook driver
            DriverManager::loadDriver(FacebookDriver::class);

            // Create and return the BotMan instance
            $config = config('botman.facebook'); // Assuming you have a botman.php config file
            return BotManFactory::create($config);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
