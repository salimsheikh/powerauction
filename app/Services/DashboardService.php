<?php

namespace App\Services;

use App\Models\{Player,Team,League,SoldPlayer,Sponsor,UnsoldPlayer};

use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function getDashboardData(): array
    {
        return Cache::remember('dashboard_data', now()->addMinutes(2), function () {
            return [
                'current_league_name' => League::getCurrentLeagueName(),
                'total_teams' => Team::count(),
                'total_players' => Player::count(),
                'sold_players' => SoldPlayer::count(),
                'unsold_players' => UnsoldPlayer::count(),
                'total_sponsors' => Sponsor::count(),
            ];
        });
    }
}
