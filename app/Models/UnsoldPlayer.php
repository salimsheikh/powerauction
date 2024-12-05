<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnsoldPlayer extends Model
{
    use HasFactory;

    protected $fillable = ['player_id','category_id'];
}
