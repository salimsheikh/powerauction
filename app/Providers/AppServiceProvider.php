<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\RateLimiter;

use Illuminate\Auth\Events\Login;
use App\Listeners\ClearCacheListener;
use Illuminate\Support\Facades\Event;

use App\Events\CacheClearEvent;
use App\Listeners\LogUserLogin;

use App\Services\SettingsService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingsService::class, function ($app) {
            return new SettingsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191); // Restricts default string length to fit within 1000 bytes

        //$this->configureRateLimiting();
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('per-second', function (Request $request) {
            return RateLimiter::perSecond(1)->response(function () {
                return response()->json(['error' => 'Too many requests. Please wait.'], 429);
            });
        });

        Event::listen(CacheClearEvent::class, ClearCacheListener::class);

        try {
            // Register the listener for the Login event
            Event::listen(Login::class, LogUserLogin::class);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Login event failed.'], 429);
        }


        
    }
}
