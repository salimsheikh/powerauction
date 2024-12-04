<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldPlayer extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'sold_players'; // Specifies the table name
    protected $primaryKey = 'id'; // Specifies the primary key
    public $timestamps = true; // Enables timestamp columns (created_at and updated_at)

    protected $fillable = [
        'player_id',
        'team_id',
        'league_id',
        'category_id',
        'sold_price'
    ];  
}
