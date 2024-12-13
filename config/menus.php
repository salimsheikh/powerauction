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
            'permission' => 'player-page-view'
        ],
        [
            'label' => 'Teams',
            'route_name' => 'teams.index',
            'active_routes' => ['teams.index','team.players.index'],
            'permission' => 'team-page-view'
        ],
        [
            'label' => 'Leagues',
            'route_name' => 'leagues.index',
            'active_routes' => ['leagues.index'],
            'permission' => 'league-page-view'
        ],
        [
            'label' => 'Sponsors',
            'route_name' => 'sponsors.index',
            'active_routes' => ['sponsors.index'],
            'permission' => 'sponsor-page-view'
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
            'permission' => 'setting-page-view'
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
            'permission' => 'user-page-view'
        ],
        [
            'label' => 'User Roles',
            'route_name' => 'user-roles',
            'active_routes' => ['user-roles'],
            'permission' => 'user-role-page-view'
        ],        
        [
            'label' => 'Permissions',
            'route_name' => 'permissions',
            'active_routes' => ['permissions'],
            'permission' => 'user-permission-page-view'
        ]
    ];
?>