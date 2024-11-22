<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Player;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'base_price',
        'description',
        'color_code',
        'status',
        'created_by',
        'updated_by'
    ];

    // One-to-Many relationship with Player
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    // Override the delete method
    public function delete()
    {
        if ($this->players()->exists()) {
            throw new \Exception('This category cannot be deleted because it is associated with players.');
        }

        parent::delete();
    }
}
