<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NewsCollectorService\TheGuardianNewsCollector;
use App\Services\NewsCollectorService\NyTimesNewsCollector;

class NewsCollectorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TheGuardianNewsCollector::class, function ($app) {
            return new TheGuardianNewsCollector();
        });
        $this->app->singleton(NyTimesNewsCollector::class, function ($app) {
            return new NyTimesNewsCollector();
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
