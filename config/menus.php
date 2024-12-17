<?php
    return [
        [
            'label' => 'Dashboard',
            'route_name' => 'dashboard',
            'active_routes' => ['dashboard'],
            'permission' => 'dashboard-page-view',
            'position' => 'header'
        ],
        [
            'label' => 'Category',
            'route_name' => 'categories.index',
            'active_routes' => ['categories.index'],
            'permission' => 'category-page-view',
            'position' => 'header'
        ],
        [
            'label' => 'Players',
            'route_name' => 'players.index',
            'active_routes' => ['players.index'],
            'permission' => 'player-page-view',
            'position' => 'header'
        ],
        [
            'label' => 'Teams',
            'route_name' => 'teams.index',
            'active_routes' => ['teams.index','team.players.index'],
            'permission' => 'team-page-view',
            'position' => 'header'
        ],
        [
            'label' => 'Leagues',
            'route_name' => 'leagues.index',
            'active_routes' => ['leagues.index'],
            'permission' => 'league-page-view',
            'position' => 'header'
        ],
        [
            'label' => 'Sponsors',
            'route_name' => 'sponsors.index',
            'active_routes' => ['sponsors.index'],
            'permission' => 'sponsor-page-view',
            'position' => 'header'
        ],
        [
            'label' => 'Biddings',
            'route_name' => 'bidding.index',
            'active_routes' => ['bidding.index'],
            'permission' => 'bidding-page-view',
            'position' => 'header'
        ],
        [
            'label' => 'Profile',
            'route_name' => 'profile.edit',
            'active_routes' => ['profile.edit'],
            'permission' => 'profile-page-view',
            'position' => 'side'
        ],
        [
            'label' => 'Settings',
            'route_name' => 'settings.index',
            'active_routes' => ['settings.index'],
            'permission' => 'setting-page-view',
            'position' => 'side'
        ],
        [
            'label' => 'Clear Cache',
            'route_name' => 'clear-cache',
            'active_routes' => ['clear-cache'],
            'permission' => 'clear-cache-page-view',
            'position' => 'side'
        ],
        [
            'label' => 'Users',
            'route_name' => 'users',
            'active_routes' => ['users'],
            'permission' => 'user-page-view',
            'position' => 'side'
        ],
        [
            'label' => 'User Roles',
            'route_name' => 'user-roles',
            'active_routes' => ['user-roles'],
            'permission' => 'user-role-page-view',
            'position' => 'side'
        ],        
        [
            'label' => 'Permissions',
            'route_name' => 'permissions',
            'active_routes' => ['permissions'],
            'permission' => 'user-permission-page-view',
            'position' => 'side'
        ],
        [
            'label' => 'Auction',
            'route_name' => 'auction.index',
            'active_routes' => ['auction.index'],
            'permission' => 'auction-page-view',
            'position' => 'header'
        ],
        [
            'label' => 'Team Details',
            'route_name' => 'team.page.details',
            'active_routes' => ['team.page.details'],
            'permission' => 'team-page-details',
            'position' => 'header'
        ],
        [
            'label' => 'Auction Rules',
            'route_name' => 'auction.rules',
            'active_routes' => ['auction.rules'],
            'permission' => 'auction-rules',
            'position' => 'header'
        ],

        
    ];
?>