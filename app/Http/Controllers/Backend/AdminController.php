<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Plan,Team,SoldPlayer};
use Illuminate\Support\Facades\{Session,Artisan};
use Illuminate\Support\Facades\Auth;

use App\Services\DashboardService;

class AdminController extends Controller
{

    public function __construct(){
        // $this->middleware('permission:permission-page-view');
    }


    public function clearCache(){

        // Or clear all cache
        Artisan::call('optimize:clear');

        return back()->with('status', __('Cache Cleared'));
    }

    public function dashboard(DashboardService $dashboardService){
        $userId = Auth::id();
        //\Log::info("userId: " . $userId);
        
        $data = array();
        $data = $dashboardService->getDashboardData();
        return view('dashboard',compact('data'));
    }

    public function teamPlayers(Request $request, $team_id){

        $data = [];

        $data['team_id'] = $team_id;

        $team = Team::select('team_name')->find($team_id);

        $data['team_name'] = $team->team_name;
        
        $data['player_ids'] = SoldPlayer::where('team_id', $team_id)->pluck('player_id')->toArray();            

        Session::put('view_team_id',$team_id);

        return view('admin.team-players',$data);
    }

    public function teamDetails(){
        $data = [];

        $team_id = 0;

        $data['team_id'] = $team_id;

        // $team = Team::select('team_name')->find($team_id);

        $data['team_name'] = '';
        
        $data['player_ids'] = '';

        Session::put('view_team_id',$team_id);

        return view('admin.team-details');
    }

    public function auctionRulesPage(){
        $data = [];
        $data['title'] = __('Auction Rules');
        $data['pageData'] = \App\Models\Setting::getSetting('auction_rules','');   ;
        return view('admin.page',$data);
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

    public function users(){           
        return view('admin.users');
    }

    public function userRoles(){
        return view('admin.user-roles');
    }

    public function permissions(){
        return view('admin.user-permissions');
    }

    
}
