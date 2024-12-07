<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

use App\Models\League;
use App\Models\Player;
use App\Models\BidSession;
use App\Models\UnsoldPlayer;
use App\Models\Bid;
use App\Models\SoldPlayer;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $leagueId = Session::get('league_id');

        $categoryId = Session::get('category_id');

        if(empty($leagueId) || $leagueId <= 0){
            return redirect()->route('leagues.index');
        }

        // Retrieve the league name by ID
        $leagueName = DB::table('league')->where('id', $leagueId)->value('league_name');

        // Check if a category_id is provided in the query parameters
        if ($request->isMethod('post') && $request->has('category_id')) {
            Session::put('category_id', $request->category_id);
            $categoryId = $request->category_id;
        }
        
        if($categoryId > 0){
            // Filter players by the selected category_id
            $players = Player::with('category')->where('category_id', $categoryId)->get();
        }else{
            // If no category is selected, display all players
            $players = Player::with('category')->get(); 
        }   

        return view('admin.auction', compact('leagueName','players','leagueId','categoryId'));
    }

    public function setLeagueId(Request $requst, $id)
    {   
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

    public function biddingStart(Request $request, $id){

        $team_id = 0;
        $session_id = $id;
        $current_time =now();
                   
        $session = BidSession::select('id','league_id','player_id','start_time','end_time','status')->find($session_id);
        
        if(!$session){
            return redirect()->route('dashboard');
        }

        $player_id = $session->player_id;
        $league_id = $session->league_id;
        $start_time = $session->start_time;
        $end_time = $session->end_time;
        $status = $session->status;

        // Enable query log
        //DB::enableQueryLog();
            
        if($status == 'active'){
            if($end_time >= $current_time){
                $league = League::find($league_id); // Find the record by ID
                $league->increment('auction_view'); // Increment the column by 1

                // $player_data = Player::get();
                // $player_data = $player_data ? $player_data->toArray() : null;
                // $unsoldplayer = array_column($player_data, 'players_id');

                $team_id = SoldPlayer::where(['league_id' => $league_id, 'player_id' => $player_id])->value('team_id');                
                return $this->getBiddingPage($request,$player_id,$session_id);
            }else{
                $bid_data = Bid::where('session_id', $session_id)->orderBy('amount', 'DESC')->first(); // Use first() to get only the highest bid

                if (!empty($bid_data)) {
                    $session_id = $bid_data->session_id;
                    $player_id = $bid_data->player_id;

                    // Get bid session and player data
                    $bid_session = BidSession::where('id',$session_id)->first();                   
                    $bid_session = $bid_session ? $bid_session->toArray() : null;
                    $player_id = $bid_session != null ? $bid_session['player_id'] : 0;                    

                    $player_data = Player::select('category_id')->where('id',$player_id)->first();
                    $player_data = $player_data ? $player_data->toArray() : null;
                    $category_id = $player_data != null ? $player_data['category_id'] : 0;                    
                    
                    $data = ['players_id' => $bid_session['player_id'], 'category_id' => $player_data['category_id'], 'teams_id' => $bid_data['team_id'], 'league_id' => $bid_session['league_id'], 'sold_price' => $bid_data['amount']];
                    $result = SoldPlayer::create($data);
                    if($result){
                        // Update bid_sessions status to 'closed'
                        $updateBidSession = BidSession::find($bid_session['session_id']);
                        $updateBidSession->update(['status' => 'closed']);

                        // Update bids table to set is_winner for the highest bid
                        $updateBid = Bid::find($bid_session['session_id']);
                        $updateBid->update(['is_winner' => 1]);

                        // Prepare success response
                        $response = ['status' => 'success', 'message' => 'Successfully added the bid!'];
                    }else {
                        // Prepare error response if insertion failed
                        $response = ['status' => 'error', 'message' => 'Cancelled'];
                    }
                } else {

                    // Get bid session and player data
                    $bid_session = BidSession::where('id',$session_id)->first();                   
                    $bid_session = $bid_session ? $bid_session->toArray() : null;
                    $player_id = $bid_session != null ? $bid_session['player_id'] : 0;                    

                    $player_data = Player::select('category_id')->where('id',$player_id)->first();
                    $player_data = $player_data ? $player_data->toArray() : null;
                    $category_id = $player_data != null ? $player_data['category_id'] : 0;    
                    // If no bid data found, handle as unsold
                    // Get bid session and player data

                    $data = ['player_id' => $player_id, 'category_id' => $category_id];

                     // Insert data into unsold table and check if successful
                    $result = UnsoldPlayer::create($data);
                    if($result){
                        $bidSessionObj = BidSession::find($session_id);
                        $bidSessionObj->update(['status' => 'closed']);
                        $response = ['status' => 'success', 'message' => 'Successfully added the bid!'];
                    } else {
                        // Prepare error response if insertion failed
                        $response = ['status' => 'error', 'message' => 'Cancelled'];
                    }
                }
                return redirect()->route('bidding.index');
            }            
        }
        return redirect()->route('dashboard');
    }

    function getBiddingPage($request, $player_id = 0, $session_id = 0){

        $leagueId = Session::get('league_id');

        $categoryId = Session::get('category_id');

        $team_id = Session::get('owner_team_id');

        if(empty($leagueId) || $leagueId <= 0){
            return redirect()->route('leagues.index');
        }

        // Retrieve the league name by ID
        $leagueName = DB::table('league')->where('id', $leagueId)->value('league_name');

        // Check if a category_id is provided in the query parameters
        if ($request->isMethod('post') && $request->has('category_id')) {
            Session::put('category_id', $request->category_id);
            $categoryId = $request->category_id;
        }
        
        $players = Player::with('category');

        if($categoryId > 0){
            // Filter players by the selected category_id
            $players->where('category_id', $categoryId);
        }

        if($player_id > 0){
            $players->where('id', $player_id);
        }
        
        $players = $players->get();

        return view('admin.auction-bid', compact('leagueName','players','leagueId','categoryId','session_id','team_id','player_id'));
    }

    function biddingList(){
        return view('admin.bidding');

    }
}
