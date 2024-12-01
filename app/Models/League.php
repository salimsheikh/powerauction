<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Team;

class League extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'league';

    // Primary key
    protected $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    // Fillable fields
    protected $fillable = [
        'league_name',
        'description',
        'status',
        'auction_view',
        'category',
        'unsold',
        'created_by',
        'updated_by',
    ];

    // One-to-Many relationship with Player
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class,'league_id');
    }

    // Override the delete method
    public function delete()
    {
        if ($this->teams()->exists()) {
            throw new \Exception('This league cannot be deleted because it is associated with team.');
        }
        parent::delete();
    }
}
