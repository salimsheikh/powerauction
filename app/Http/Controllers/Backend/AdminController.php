<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Team;
use App\Models\SoldPlayer;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function teamPlayers(Request $request, $team_id){

        $data = [];

        $data['team_id'] = $team_id;

        $team = Team::select('team_name')->find($team_id);

        $data['team_name'] = $team->team_name;
        
        $data['player_ids'] = SoldPlayer::where('team_id', $team_id)->pluck('player_id')->toArray();            

        Session::put('view_team_id',$team_id);

        return view('admin.team-players',$data);
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
