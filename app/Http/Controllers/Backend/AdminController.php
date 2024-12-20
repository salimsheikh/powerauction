<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Plan,Team,SoldPlayer,Player};
use Illuminate\Support\Facades\{Session,Artisan,Auth,Cache};

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
        $data = array();
        $data = $dashboardService->getDashboardData();
        return view('dashboard',compact('data'));
    }

    public function teamPlayers(Request $request, $team_id){

        Session::put('view_team_id',$team_id);

        // Dynamic cache duration from config or default to 12 hours
        $cacheDuration = now()->addMinutes(config('cache.sold_player_team_data_duration', 720));

        // Attempt to retrieve cached data or query the database
        $data = Cache::remember("sold_player_team_{$team_id}", $cacheDuration, function () use ($team_id){

            $data = [];

            $data['team_id'] = $team_id;

            $team = Team::select('team_name')->find($team_id);

            $data['team_name'] = $team->team_name;
            
            $data['player_ids'] = SoldPlayer::where('team_id', $team_id)->pluck('player_id')->toArray();

            return $data;
        });
        
        return view('admin.team-players',$data);
    }

    public function teamDetails(){
        $data = [];

        // Define columns for the view        
        $data['columns'] = [
            'image_thumb' => '',
            'uniq_id' => __('Unique Id'),
            'player_name' => __('Name'),
            'category_name' => __('Category'),
            'base_price' => __('Base Points'),
            'sold_price' => __('Purchase Points'),
        ];
        
        // Dynamic cache duration from config or default to 12 hours
        $cacheDuration = now()->addMinutes(config('cache.team_data_duration', 720));

        try {
            // Attempt to retrieve cached data or query the database
            $data['teams'] = Cache::remember("team_with_player", $cacheDuration, function () {
                return Team::getTeamsWithPlayers() ?? [];
            });
            
        } catch (\Exception $e) {
            // Log the error and provide a fallback
            \Log::error('Error retrieving team details: '.$e->getMessage());
            $data['teams'] = []; // Fallback to an empty array
        }

        // Return the view with the prepared data
        return view('admin.team-details', $data);

    }

    public function auctionRulesPage(){
        $data = [];
        $data['title'] = __('Auction Rules');
        $data['pageData'] = setting('auction_rules');
        return view('admin.page',$data);
    }

    public function termConditionPage(){
        $data = [];
        $data['title'] = __('Term & Condition');
        $data['pageData'] = setting('terms_condition');
        return view('admin.page',$data);
    }

    public function privacyPolicyPage(){
        $data = [];
        $data['title'] = __('Privacy Policy');
        $data['pageData'] = setting('privacy_policy');
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
        
        $data = [];

        // Dynamic cache duration from config or default to 12 hours
        $cacheDuration = now()->addMinutes(config('cache.suport_data_duration', 720));

        // Attempt to retrieve cached data or query the database
        $data['plans'] = Cache::remember("plans", $cacheDuration, function () {
            return Plan::select('id','amount')->where('status', 'publish')->orderBy('order', 'ASC')->get();
        });
       
        return view('admin.teams',$data);
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
