<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\League;

class Team extends Model
{
    use HasFactory;

    protected $table = 'teams';

    protected $primaryKey = 'id';

    protected $fillable = [
        'team_name',
        'team_logo',
        'team_logo_thumb',
        'virtual_point',
        'league_id',
        'owner_id',
        'status',
        'created_by',
        'updated_by',
    ];   

    function users(){
        return $this->belongsTo(User::class, 'owner_id');
    }

    function league(){
        return $this->belongsTo(League::class, 'league_id');
    }

    // Accessor for category_name
    public function getLeagueNameAttribute(): ?string
    {
        return $this->league?->league_name; // Safe navigation to avoid null errors
    }

    function soldPlayers(){
        //return $this->belongsTo(SoldPlayer::class, 'team_id');
        return $this->hasMany(SoldPlayer::class, 'team_id');
    }

    
    public function players()
    {
        return $this->hasManyThrough(Player::class, SoldPlayer::class, 'team_id', 'id', 'id', 'player_id');
    }

    public static function getTeamsWithPlayers()
    {
        $playerWith = [
            'soldPlayers:id,team_id,player_id,sold_price',
            'players.category:id,category_name,base_price',
        ];       
        
        $teams = self::with($playerWith)->get();
        
        return $teams->map(function ($team) {
            $soldPlayers = $team->soldPlayers;
            $points_spent = $team->soldPlayers->sum('sold_price');
            $points_remaining = $team->virtual_point - $points_spent;
            $exceeded_by = max(0, $points_spent - $team->virtual_point);
            return [
                'team_name' => $team->team_name,
                'team_logo' => $team->team_logo,
                'virtual_point' => $team->virtual_point,
                'points_spent' => $points_spent,
                'points_remaining' => $points_remaining,
                'exceeded_by' => $exceeded_by,
                'players' => $team->players->map(function ($player) use ($soldPlayers) {
                    $sold_price = $soldPlayers->firstWhere('player_id', $player->id)?->sold_price ?? 0;
        
                    return [
                        'id' => $player->id,
                        'player_id' => $player->id,
                        'uniq_id' => $player->uniq_id,
                        'player_name' => $player->player_name,
                        //'image' => $player->image,
                        'image_thumb' => $player->image_thumb,                        
                        'category_name' => $player->category->category_name ?? null,
                        'base_price' => $player->category->base_price ?? null,
                        'sold_price' => $sold_price,
                    ];
                }),
            ];
        });
    }

    public static function getTeams($query){

        // Start the query builder for the Team model
        $itemQuery = self::query()
            ->select(
                'teams.*',
                'users.email as owner_email',
                'users.phone as owner_phone',
                'users.name as owner_name'
            )
            ->join('users', 'teams.owner_id', '=', 'users.id') // Join with users table
            ->with(['league:id,league_name','soldPlayers:team_id,sold_price']); // Eager load the League relationship if needed
            //,'sold_players'
        // Apply search filters if a query exists
        if ($query) {
            $itemQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('team_name', 'like', '%' . $query . '%')
                    ->orWhereHas('league', function ($leagueQuery) use ($query) {
                        $leagueQuery->where('league_name', 'like', '%' . $query . '%');
                    })
                    ->orWhere(function ($userQuery) use ($query) {
                        // Since users table is joined, directly filter on its columns
                        $userQuery->where('users.name', 'like', '%' . $query . '%')
                            ->orWhere('users.email', 'like', '%' . $query . '%')
                            ->orWhere('users.phone', 'like', '%' . $query . '%');
                    });
            });
        }

        // Filter by status and sort by creation date
        $itemQuery->where('teams.status', 'publish')->orderBy('teams.team_name', 'asc');

        $list_per_page = intval(setting('list_per_page', 10));

        // Paginate the results
        $items = $itemQuery->paginate($list_per_page);        

        $items->map(function($item){
            $item->league_name = $item?->league?->league_name;
            $sold_price = $item->soldPlayers->firstWhere('team_id', $item->id)?->sold_price ?? 0;            
            $item->remaining_points = $item->virtual_point - $sold_price;
            return $item;
        });

        return $items;
    }
        
}
