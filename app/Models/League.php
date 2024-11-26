<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
