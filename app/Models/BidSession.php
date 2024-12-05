<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Support\Str;

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

}
