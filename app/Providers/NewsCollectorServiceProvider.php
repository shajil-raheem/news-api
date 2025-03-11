<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NewsCollectorService\TheGuardianNewsCollector;
use App\Services\NewsCollectorService\NyTimesNewsCollector;
use App\Services\NewsCollectorService\OpenNewsCollector;

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
        $this->app->singleton(OpenNewsCollector::class, function ($app) {
            return new OpenNewsCollector();
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
