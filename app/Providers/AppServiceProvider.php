<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('pythonServiceUrl', rtrim(env('PYTHON_SERVICE_URL', 'http://localhost:8001'), '/'));

        // Internal event ingestion from the Python service. Cooldown gates in Python
        // keep the real rate far lower; this is just a safety valve.
        RateLimiter::for('internal-events', fn () => Limit::perMinute(120));
    }
}
