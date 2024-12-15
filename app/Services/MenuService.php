<?php
namespace App\Services;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class MenuService
{
    public function filterMenus(array $menus): array
    {
        $headerMenu = [];
        $sideMenu = [];

        foreach ($menus as $menu) {
            if ($menu['position'] == 'header') {
                $headerMenu[] = $menu;                
            }

            if ($menu['position'] == 'side') {
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
