<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerProfileType extends Model
{
    use HasFactory;

    protected $fillable = ['profile_type', 'name', 'order', 'created_by', 'updated_by'];

    public function player()
    {
        return $this->belongsTo(Player::class,'profile_type');
    }
}
