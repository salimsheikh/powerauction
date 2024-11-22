<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $table = 'players'; // Specifies the table name
    protected $primaryKey = 'id'; // Specifies the primary key
    public $timestamps = true; // Enables timestamp columns (created_at and updated_at)

    // Columns that are mass assignable
    protected $fillable = [
        'uniq_id',
        'player_name',
        'nickname',
        'mobile',
        'email',
        'category_id',
        'dob',
        'image',
        'image_thumb',
        'bat_type',
        'ball_type',
        'type',
        'profile_type',
        'style',
        'last_played_league',
        'address',
        'city',
        'order_id',
        'status',
        'created_by',
        'updated_by',
    ];

    // Columns that should not be mass assignable
    protected $guarded = [
        // You can add columns that should be protected here (if any)
    ];
}
