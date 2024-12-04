<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class AdminController extends Controller
{
    public function teamPlayers(){
        return view('admin.team-players');
    }

    public function categories(){
        return view('admin.categories');
    }

    public function league(){
        return view('admin.league');
    }

    public function players(){
        return view('admin.players');
    }

    public function sponsors(){
        return view('admin.sponsors');
    }

    public function teams(){
        $plans = Plan::select('id','amount')->where('status', 'publish')->orderBy('order', 'ASC')->get();
        return view('admin.teams',compact('plans'));
    }
}
