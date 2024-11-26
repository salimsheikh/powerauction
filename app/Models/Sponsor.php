<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'sponsors'; // Specifies the table name
    protected $primaryKey = 'id'; // Specifies the primary key
    public $timestamps = true; // Enables timestamp columns (created_at and updated_at)

    protected $fillable = [
        'sponsor_name',
        'sponsor_logo',
        'sponsor_description',
        'sponsor_website_url',
        'sponsor_type',
        'status',
        'created_by',
        'updated_by',
    ];  

    public function sponsorTypes()
    {
        return $this->belongsTo(SponsorType::class, 'sponsor_type', 'slug');
    }

    // Accessor for category_name
    public function getSponsorTypeNameAttribute(): ?string
    {
        return $this->sponsorTypes?->name;
    }
}
