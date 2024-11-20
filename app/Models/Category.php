<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
