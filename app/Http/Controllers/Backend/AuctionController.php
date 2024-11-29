<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index(){
        return view('admin.auction');
    }

    public function setLeagueId(Request $requst, $id){

        dd($id);

    }
}
