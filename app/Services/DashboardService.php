<?php

namespace App\Services;

use App\Models\{Player,Team,League,SoldPlayer,Sponsor,UnsoldPlayer};

use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function getDashboardData(): array
    {
        // Dynamic cache duration from config or default to 12 hours
        $cacheDuration = now()->addMinutes(config('cache.dashboard_data_duration', 720));

        try {
            return Cache::remember('dashboard_data', $cacheDuration, function () {
                return [
                    'current_league_name' => League::getCurrentLeagueName(),
                    'total_teams' => Team::count(),
                    'total_players' => Player::count(),
                    'sold_players' => SoldPlayer::count(),
                    'unsold_players' => UnsoldPlayer::count(),
                    'total_sponsors' => Sponsor::count(),
                ];
            });            
        } catch (\Exception $e) {
            // Log the error and provide a fallback
            \Log::error('Error retrieving dashboard: '.$e->getMessage());
            return []; // Fallback to an empty array
        }       
    }
}
