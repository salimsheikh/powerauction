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

    public function getAuctionExpireMinutes(){
        $v = Setting::where('option_name', 'auction_expire_minutes')->value('option_value');
        $v = $v == "" ? 2 : $v;
        return $v;
    }
}
