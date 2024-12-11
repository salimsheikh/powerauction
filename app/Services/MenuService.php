<?php
namespace App\Services;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class MenuService
{
    public function filterMenus(array $menus, array $headerRoutes, array $sideMenuRoutes): array
    {
        $headerMenu = [];
        $sideMenu = [];

        foreach ($menus as $menu) {
            if (in_array($menu['route_name'], $headerRoutes)) {
                $headerMenu[] = $menu;
            }

            if (in_array($menu['route_name'], $sideMenuRoutes)) {
                $sideMenu[] = $menu;
            }
        }

        $headerMenu = $this->filterMenu($headerMenu);

        $sideMenu = $this->filterMenu($sideMenu);

        return ['header_menu' => $headerMenu, 'dropdown_menu' => $sideMenu];
    }

    public function filterMenu(array $menu): array
    {
        if (auth()->check()) {
            return array_filter($menu, function ($item) {
                // Check permission using Spatie
                $permissionCheck = auth()->user()->can($item['permission']);
                
                // Check if the route exists
                $routeCheck = Route::has($item['route_name']);
                
                return $permissionCheck && $routeCheck;
            });
        }
        
        // Return empty menu or handle as needed if the user is not authenticated
        return [];
    }
}
