<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BidSession extends Model
{
   
    use HasFactory;

    protected $table = 'bid_sessions';

    protected $primaryKey = 'id';
    
    public $incrementing = true; // Ensure auto-incrementing

    protected $keyType = 'int'; // Ensure the primary key type matches your database

    public $timestamps = true;

    protected $fillable = [
        'league_id',
        'player_id',
        'start_time',
        'end_time',
        'status',
        'created_by',
        'updated_at'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Many-to-One relationship with Category
    public function league()
    {
        return $this->belongsTo(League::class, 'league_id');
    }  

    // Many-to-One relationship with Category
    public function players()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function getFormattedStartTimeAttribute()
    {
        return Carbon::parse($this->attributes['start_time'])->format('Y-m-d H:i:s');
    }

    public function getFormattedEndTimeAttribute()
    {
        return Carbon::parse($this->attributes['end_time'])->format('Y-m-d H:i:s');
    }

    public function getFormattedStatusAttribute(){
        return  Str::headline($this->attributes['status']);
    }

    /**
     * Get player data based on the session ID.
     *
     * @param int $sessionId
     * @return array|null
     */
    public static function getPlayerDataBySession(int $sessionId): ?array
    {
        $playerData = DB::table('bid_sessions as s')
            ->select('s.player_id', 'p.category_id', 'c.base_price')
            ->where('s.id', $sessionId)
            ->join('players as p', 'p.id', '=', 's.player_id')
            ->join('categories as c', 'c.id', '=', 'p.category_id')
            ->first();

        // Convert the result to an array and return, or return null if no data found
        return $playerData ? (array) $playerData : null;
    }

    /**
     * Close expired sessions.
     *
     * @return int Number of records updated
     */
    public static function closeExpiredSessions(): int
    {
        return static::where('status', 'active')
            ->where('end_time', '<=', now())
            ->update(['status' => 'closed']);
    }

    // Method to build and apply search filters
    public static function applySearchFilters(Builder $query, string $searchQuery)
    {
        return $query->where(function ($query) use ($searchQuery) {
            $query->where('start_time', 'like', '%' . $searchQuery . '%')
                ->orWhere('end_time', 'like', '%' . $searchQuery . '%')
                ->orWhere('status', 'like', '%' . $searchQuery . '%')
                ->orWhereHas('league', function ($leagueQuery) use ($searchQuery) {
                    $leagueQuery->where('league_name', 'like', '%' . $searchQuery . '%');
                })
                ->orWhereHas('players', function ($playerQuery) use ($searchQuery) {
                    $playerQuery->where('player_name', 'like', '%' . $searchQuery . '%');
                });
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($bidSession) {
            if ($bidSession->status === 'active' && $bidSession->end_time <= now()) {
                $bidSession->status = 'closed';
            }
        });
    }

}
