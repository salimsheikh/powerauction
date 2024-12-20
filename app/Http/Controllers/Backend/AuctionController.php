<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\{Session,DB,Lang,Cache,Auth};
use App\Events\CacheClearEvent;
use Carbon\Carbon;

use App\Models\{League,Player,BidSession,UnsoldPlayer,Bid,SoldPlayer};

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        // Check if the user is an admin
        if (!Auth::user()->hasRole('Administrator')) {
            return $this->auctionPlayer(); // Non-admin logic
        }

        $leagueId = Session::get('league_id');
        $categoryId = Session::get('category_id');

        if (empty($leagueId) || $leagueId <= 0) {
            return redirect()->route('leagues.index');
        }

        $leagueName = League::getLeagueName($leagueId);

        if ($request->isMethod('post') && $request->has('category_id')) {
            League::updateCategory($leagueId, $request->category_id);
            Session::put('category_id', $request->category_id);
            $categoryId = $request->category_id;
        }

        $players = Player::getPlayers($categoryId);     

        return view('admin.auction', compact('leagueName', 'players', 'leagueId', 'categoryId'));
    }     

    public function auctionPlayer(){

        $league = League::select('id','league_name','auction_view')->where('status', 1)->first();
        $league = $league != null ? $league->toArray() : array();       

        $league_id = $league['id'];
        $leagueName = $league['league_name'];
        $player_id = $league['auction_view'];
        $categoryId = 0;

        $players = Player::getPlayers($categoryId,$player_id,$league_id);       

        return view('admin.auction-user', compact('leagueName','players','league_id','player_id'));
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

        // Dispatch event to clear specific cache
        // event(new CacheClearEvent('dashboard_data'));
        Cache::forget('dashboard_data');

        // Redirect to the route named 'auction.index'
        return redirect()->route('auction.index');
    }

    public function biddingStart(Request $request, $id){

        $team_id = 0;
        $session_id = $id;
        $current_time = now();
                   
        $session = BidSession::select('id','league_id','player_id','start_time','end_time','status')->find($session_id);
        
        if(!$session){
            return redirect()->route('dashboard');
        }

        $player_id = $session->player_id;
        $league_id = $session->league_id;
        $start_time = $session->start_time;
        $end_time = $session->end_time;
        $status = $session->status;       
            
        if($status == 'active'){
            if($end_time > $current_time){
                League::updateAuctionViewAmount($league_id, $player_id);
                $team_id = SoldPlayer::where(['league_id' => $league_id, 'player_id' => $player_id])->value('team_id');                
                return $this->getBiddingPage($request,$player_id,$session_id,$start_time,$end_time);
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
                        $ssid = isset($bid_session['session_id']) ? $bid_session['session_id'] : $session_id;
                        // Update bid_sessions status to 'closed'
                        $updateBidSession = BidSession::find($ssid);
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

    function getBiddingPage($request, $player_id = 0, $session_id = 0,$sessionStartTime = '',$sessionEndTime = ''){

        $leagueId = Session::get('league_id');

        $categoryId = Session::get('category_id');

        $team_id = Session::get('owner_team_id');

        if(empty($leagueId) || $leagueId <= 0){
            return redirect()->route('leagues.index');
        }

        // Retrieve the league name by ID        
        $leagueName = League::getLeagueName($leagueId);

        // Check if a category_id is provided in the query parameters
        if ($request->isMethod('post') && $request->has('category_id')) {
            Session::put('category_id', $request->category_id);
            $categoryId = $request->category_id;
        }
      
        $players = Player::getPlayers($categoryId,$player_id,$leagueId);

        $serverTime = now();

        $remainingMinutes = "";

        $remainingMinutes = $this->getRemaingTime($sessionEndTime, $serverTime);

        return view('admin.auction-bid', compact('leagueName','players','leagueId','categoryId','session_id','team_id','player_id','sessionStartTime','sessionEndTime','serverTime','remainingMinutes'));
    }

    function biddingList(){
        return view('admin.bidding');
    }

    private function getRemaingTime($start_time, $end_time){       

        $start_time = trim($start_time); // Ensure clean input
        $end_time = trim($end_time);
    
        try {
            // Create Carbon instances using the correct format
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $start_time);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $end_time);

            // Calculate the absolute difference in seconds
            $time_difference_in_seconds = abs($end->diffInSeconds($start));

            // Convert seconds into minutes and seconds
            $remaining_minutes = intdiv($time_difference_in_seconds, 60);
            $remaining_seconds = $time_difference_in_seconds % 60;

            // Return the result as "MM:SS"
            return sprintf('%02d:%02d', $remaining_minutes, $remaining_seconds);
        } catch (\Exception $e) {
            // Handle errors gracefully
            return "00:00"; // Default value on error
        }
    }
}
