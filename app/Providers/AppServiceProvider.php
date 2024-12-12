<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;

use Illuminate\Auth\Events\Login;


use App\Listeners\ClearCacheListener;
use App\Listeners\LogUserLogin;

use App\Events\CacheClearEvent;

use App\Services\SettingsService;
use App\Services\MenuService;

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

        //\Log::info('Auth Check:', ['check' => auth()->check()]);
        //\Log::info('User:', ['user' => auth()->user()]);
                

        $menus = config('menus'); // Or fetch from the database

        $filters = config('menu_filters');
        $headerRoutes = $filters['header'];
        $sideMenuRoutes = $filters['side_menu'];
    
        $menuService = app(MenuService::class);
        $filteredMenus = $menuService->filterMenus($menus, $headerRoutes, $sideMenuRoutes);

        // $headerMenu = $menuService->filterMenu($filteredMenus['header_menu']);
        // $sideMenu = $menuService->filterMenu($filteredMenus['dropdown_menu']);
    
        View::share($filteredMenus);
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
