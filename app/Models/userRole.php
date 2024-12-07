<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class userRole extends Model
{
    use HasFactory;

    protected $fillable = ['slug','name', 'permission','description','status','created_by','updated_by'];
}
