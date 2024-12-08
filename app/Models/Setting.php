<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_name',
        'option_value'
    ];

    public static function getSetting($k,$d=null){
        $v = Setting::where('option_name', $k)->value('option_value');
        $v = $v == "" ? $d : $v;
        return $v;
    }
}
