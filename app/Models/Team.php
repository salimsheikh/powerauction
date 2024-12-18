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
        return $this->belongsTo(SoldPlayer::class, 'team_id');
    }

    
    public function players()
    {
        return $this->hasManyThrough(Player::class, SoldPlayer::class, 'team_id', 'id', 'id', 'player_id');
    }
        
}
