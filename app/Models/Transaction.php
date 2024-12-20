<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'team_id',
        'plan_id',
        'type',
        'amount',
        'created_by',
        'updated_by'
    ];

    /**
     * Relationships
     */

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class,'team_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class,'plan_id');
    }
}
