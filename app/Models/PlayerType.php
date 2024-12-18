<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerType extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'name', 'order', 'created_by', 'updated_by'];

    public function player()
    {
        return $this->belongsTo(Player::class,'type');
    }
}
