<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsorType extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'name', 'order','status', 'created_by', 'updated_by'];

    /**
     * Relationship to Sponsor
     */
    public function sponsors()
    {
        return $this->hasMany(Sponsor::class, 'sponsor_type', 'slug');
    }
}
