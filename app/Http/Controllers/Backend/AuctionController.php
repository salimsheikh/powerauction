<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

use App\Models\League;

class AuctionController extends Controller
{
    public function index(){
        $leagueId = Session::get('league_id');

        // Retrieve the league name by ID
        $leagueName = DB::table('league')->where('id', $leagueId)->value('league_name');       

        if(empty($leagueId) || $leagueId <= 0){
            return redirect()->route('leagues.index');
        }

        return view('admin.auction', ['leagueName' => 'Premier League']);
    }

    public function setLeagueId(Request $requst, $id){

        
        // Step 1: Set a session value
        Session::put('league_id', $id);
        Session::put('cat_id', '');

        // Step 2: Update all rows in the `leagues` table
        DB::table('league')->update(['status' => 0]); // Set status to 0 for all rows

        // Step 3: Update specific row's status to 1
        // Replace '1' with your specific condition
        DB::table('league')->where('id', $id)->update(['status' => 1]);

        // Redirect to the route named 'auction.index'
        return redirect()->route('auction.index');
    }
}
