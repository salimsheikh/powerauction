<?php
    return [
        [
            'label' => 'Dashboard',
            'route_name' => 'dashboard',
            'active_routes' => ['dashboard'],
            'permission' => 'dashboard-page-view'
        ],
        [
            'label' => 'Category',
            'route_name' => 'categories.index',
            'active_routes' => ['categories.index'],
            'permission' => 'category-page-view'
        ],
        [
            'label' => 'Players',
            'route_name' => 'players.index',
            'active_routes' => ['players.index'],
            'permission' => 'players-page-view'
        ],
        [
            'label' => 'Teams',
            'route_name' => 'teams.index',
            'active_routes' => ['teams.index','team.players.index'],
            'permission' => 'teams-page-view'
        ],
        [
            'label' => 'Leagues',
            'route_name' => 'leagues.index',
            'active_routes' => ['leagues.index'],
            'permission' => 'leagues-page-view'
        ],
        [
            'label' => 'Sponsors',
            'route_name' => 'sponsors.index',
            'active_routes' => ['sponsors.index'],
            'permission' => 'sponsors-page-view'
        ],
        [
            'label' => 'Biddings',
            'route_name' => 'bidding.index',
            'active_routes' => ['bidding.index'],
            'permission' => 'bidding-page-view'
        ],
        [
            'label' => 'Profile',
            'route_name' => 'profile.edit',
            'active_routes' => ['profile.edit'],
            'permission' => 'profile-page-view'
        ],
        [
            'label' => 'Settings',
            'route_name' => 'settings.index',
            'active_routes' => ['settings.index'],
            'permission' => 'settings-page-view'
        ],
        [
            'label' => 'Clear Cache',
            'route_name' => 'clear-cache',
            'active_routes' => ['clear-cache'],
            'permission' => 'clear-cache-page-view'
        ],
        [
            'label' => 'Users',
            'route_name' => 'users',
            'active_routes' => ['users'],
            'permission' => 'users-page-view'
        ],
        [
            'label' => 'User Roles',
            'route_name' => 'user-roles',
            'active_routes' => ['user-roles'],
            'permission' => 'user-roles-page-view'
        ],        
        [
            'label' => 'Permissions',
            'route_name' => 'permissions',
            'active_routes' => ['permissions'],
            'permission' => 'permissions-page-view'
        ]
    ];
?>