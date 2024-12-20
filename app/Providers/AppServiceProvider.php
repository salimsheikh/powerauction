<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{Schema,RateLimiter,View,Event,Auth};

use Illuminate\Auth\Events\Login;

use App\Listeners\{ClearCacheListener,LogUserLogin};

use App\Events\CacheClearEvent;

use App\Services\{SettingsService,MenuService};

use App\Observers\CacheFlagObserver;

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

        $this->shareMenuView();
        $this->catchFlagObserver();
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

    protected function catchFlagObserver(){
        
        $models = [
            \App\Models\Player::class,
            \App\Models\SoldPlayer::class,
            \App\Models\Team::class,
            \App\Models\Category::class,
            \App\Models\League::class,
            \App\Models\UnsoldPlayer::class,
            \App\Models\Sponsor::class,
            \App\Models\League::class,
        ];
    
        foreach ($models as $model) {
            $model::observe(CacheFlagObserver::class);
        }
    }

    protected function shareMenuView(){
        $menus = config('menus'); // Or fetch from the database
        $menuService = app(MenuService::class);
        $filteredMenus = $menuService->filterMenus($menus);
        
        // $headerMenu = $menuService->filterMenu($filteredMenus['header_menu']);
        // $sideMenu = $menuService->filterMenu($filteredMenus['dropdown_menu']);
    
        View::share($filteredMenus);   
    }
}
