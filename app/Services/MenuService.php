<?php
namespace App\Services;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class MenuService
{
    public function filterMenus(array $menus, array $headerRoutes, array $sideMenuRoutes): array
    {
        $headerMenu = [];
        $sideMenu = [];

        $i = 0;
        foreach ($menus as $menu) {
            if (in_array($menu['route_name'], $headerRoutes) && $i++ < 7) {
                $headerMenu[] = $menu;                
            }

            if (in_array($menu['route_name'], $sideMenuRoutes)) {
                $sideMenu[] = $menu;
            }
        }        

        return ['header_menu' => $headerMenu, 'dropdown_menu' => $sideMenu];
    }

    public function filterMenu(array $menu): array
    {
       
            return array_filter($menu, function ($item) {
                // Check permission using Spatie
                //$permissionCheck = auth()->user()->can($item['permission']);

                //$userId = Auth::id();
                //\Log::info("userId: " . $userId);
                // auth()->user()->can($item['permission']);

                //user()->can('edit articles');

                $permissionCheck = true;
                
                //$permissionCheck = can($menu['permission']);
                // Check if the route exists
                //$routeCheck = Route::has($item['route_name']);
                
                return $permissionCheck;
            });
       
        
        // Return empty menu or handle as needed if the user is not authenticated
        return [];
    }
}
