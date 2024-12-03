<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BidSession extends Model
{
   
    use HasFactory;

    protected $table = 'bid_sessions';

    protected $primaryKey = 'session_id';

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
}
