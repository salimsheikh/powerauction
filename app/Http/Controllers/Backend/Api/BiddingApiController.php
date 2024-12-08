<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Bid;
use App\Models\BidSession;
use App\Models\SoldPlayer;
use App\Models\UnsoldPlayer;
use App\Models\Team;
use App\Models\Player;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BiddingApiController extends Controller
{
    public function index(Request $request){

         // Close expired sessions
        BidSession::closeExpiredSessions();

        // Get search query from the request
        $searchQuery = $request->input('query', '');

        // Build the query for BidSession with necessary relationships
        $itemQuery = BidSession::with(['league', 'players']);

        // Apply search filters if there is a search query
        if ($searchQuery) {
            $query = self::applySearchFilters($query, $searchQuery);
        }

        // Order and paginate results
        $items = $itemQuery->orderBy('created_at', 'desc')->paginate(10);

        // Map items to include additional data
        $items->transform(function ($item) {
            return [
                'id' => $item->id,
                'league_name' => $item->league?->league_name ?? '',
                'player_name' => $item->players?->player_name ?? '',
                'formatted_start_time' => $item->formatted_start_time,
                'formatted_end_time' => $item->formatted_end_time,
                'formatted_status' => $item->formatted_status,
            ];
        });

        // Get table columns
        $columns = $this->get_columns();

        // Return response in JSON format
        return response()->json([
            'columns' => $columns,
            'items' => $items
        ]);
    }


    public function startBidding(Request $request){
        $userId = Auth::id();

        $expire_minutes = config('app.auction_expire_minutes', 2);
        $expire_minutes = intval(setting('auction_expire_minutes', $expire_minutes));

        // $expire_minutes = $expire_minutes/4;
        
        $data = [];
        $data['league_id'] = $request->input('league_id');
        $data['player_id'] = $request->input('player_id');
        $data['start_time'] = now();
        $data['end_time'] = Carbon::now()->addMinutes($expire_minutes)->format('Y-m-d H:i:s');
        $data['status'] = 'active';
        $data['created_by'] = $userId;       

        try {
            $url = "";
            $result = BidSession::create($data);
            if($result){
                $newInsertId = $result->id;

                $url = route('bidding.started',$newInsertId);                

                return response()->json([
                    'success' => true,
                    'status' => 'success',
                    'message' => __('Successfully Edited!.'),
                    'data' => $data,
                    'bid_id' => $result->id,
                    'url' => $url,
                ],201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => __('Bid Cancelled.'),
                    'errors' => [
                        'error' => [$e->getMessage()]
                    ]
                ], 409);
            }
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Bid Cancelled.'),
                'errors' => [
                    'error' => [$e->getMessage()]
                ]
            ], 409);
        }
    }

    function bid(Request $request){

        \Log::info("Start bid 1");

        $team_id = $request->input('team_id');
        $session_id = $request->input('session_id');
        $returnData  = [];
        if($team_id > 0){

            \Log::info("Start bid 2");

            $team_point = Team::select('virtual_point')->where('id', $team_id)->first();
            $team_point = $team_point ? $team_point->toArray() : 0;

            $virtual_point = isset($team_point['virtual_point']) ? $team_point['virtual_point'] : 0;

            $purchased_point = SoldPlayer::where('team_id',$team_id)->first();
            $purchased_point = $purchased_point ? $purchased_point->toArray() : 0;
            
            $purchased_point = isset($purchased_point['sold_price']) ? $purchased_point['sold_price'] : 0;

            $purchased_point2 = SoldPlayer::where('team_id', $team_id)->sum('sold_price');

            $remaining_point = $virtual_point - $purchased_point;
            
            $enter_amount = $request->input('amount');
            $enter_amount = $enter_amount == "" ? 0 : intval($enter_amount); 

            /*
            $returnData['virtual_point'] = $virtual_point;
            $returnData['purchased_point'] = $purchased_point;
            $returnData['remaining_point'] = $remaining_point;
            $returnData['enter_amount'] = $enter_amount;
            */

            // Check if the remaining points are greater than or equal to the amount specified in the post request
            if ($remaining_point < (int)$enter_amount) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => __('Cancelled.'),
                    'logic' => 1,
                    'returnData' => $returnData,
                    'errors' => [
                        'error' => [__('Cancelled.')]
                    ]
                ], 409);
            }

            if ($virtual_point < (int)$enter_amount) {
                // Return response as JSON
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => __('Cancelled.'),
                    'logic' => 2,
                    'returnData' => $returnData,
                    'errors' => [
                        'error' => [__('Cancelled.')]
                    ]
                ], 409);
            }

            $playerData = BidSession::getPlayerDataBySession((int) $session_id);
            $base_price = isset($playerData['base_price']) ? $playerData['base_price'] : 0;

            $returnData['base_price'] = $base_price;
                        
            if ($base_price > (int)$enter_amount) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => __('Cancelled.'),
                    'logic' => 3,
                    'returnData' => $returnData,
                    'errors' => [
                        'error' => [__('Cancelled 3')]
                    ]
                ], 409);
            }

            $userId = Auth::id();
            $data['session_id'] = $session_id;
            $data['owner_id'] = $userId;
            $data['team_id'] = $team_id;
            $data['amount'] = $enter_amount;
            $data['bid_time'] = now();
            $result = Bid::Create($data);

            if ($result) {
                $id = $result->id;
                $response = ['success' => true, 'status' => 'success', 'bid_id' => $id, 'message' => __('Successfully added the bid!'),'returnData' => $returnData];
                return response()->json($response,201);
            } else {
                $response = ['success' => false, 'status' => 'error', 'message' => __('Cancelled 4'),'errors'=>[__('Cancelled 4')],'returnData' => $returnData];
                return response()->json($response,409);
            }            
        }       

        \Log::info("Start bid 3");
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => __('Cancelled, Team id not found'),           
        ],409);
    }

    function bidWin(Request $request, $session_id){

        $session = BidSession::select('start_time','end_time','status')->find($session_id);

        \Log::info(print_r(json_encode($session),true));

        $current_time = now();
        $start_time = $session->start_time;
        $end_time = $session->end_time;
        $status = $session->status;

        if($end_time > $current_time){
            return response()->json([
                'success' => false,
                'status' => 'error',
                'start_time' => $start_time,
                'end_time' => $end_time,
                'status' => $status,
                'serverTime' => now(),
                'message' => __('Please wait!'),           
            ],409);
        }

        \Log::info(print_r($session_id,true));

        $bid_data = Bid::where('id',$session_id)->orderBy('amount','DESC')->get()->toArray();
        \Log::info(print_r($bid_data,true));

        if (!empty($bid_data)) {
            $team_id = $bid_data['team_id'];
            $amount = $bid_data['amount'];

            // Get bid session and player data
            $bid_session = BidSession::find($session_id);
            $bid_session = $bid_session ? $bid_session->toArray() : null;

            $player_id = $bid_session['player_id'];
            $bid_id = $bid_session['id'];
            $league_id = $bid_session['league_id'];

            $category_id = Player::where('id',$player_id)->value('category_id');

            // Prepare data for inserting into soldplayers table
            $data = ['players_id' => $player_id, 'category_id' => $category_id, 'team_id' => $team_id, 'league_id' => $league_id, 'sold_price' => $amount];
            // Insert data into soldplayers table and check if successful

            \Log::info(print_r($data,true));

             $result = SoldPlayer::create($data);

             \Log::info("*********022****");
            if ($result) {
                // Update bid_sessions status to 'closed'
                BiSession::where('id',$session_id)->update(['status'=>'closed']);                
                // Update bids table to set is_winner for the highest bid
                Bid::where('id',$bid_id)->update(['is_winner'=>'1']);
                // Prepare success response

                return response()->json([
                    'success' => true,
                    'status' => 'success',
                    'bidding_url' => route('bidding.index'),
                    'message' => __('Successfully added the bid!'),           
                ],200);
            } else {
                // Prepare error response if insertion failed                
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => __('Cancelled'),           
                ],409);
            }
        }else{
             // If no bid data found, handle as unsold
            // Get bid session and player data
            $bid_session = BidSession::find($session_id);
            $bid_session = $bid_session ? $bid_session->toArray() : null;

            $player_id = $bid_session['player_id'];
            $bid_id = $bid_session['id'];

            $category_id = Player::where('id',$player_id)->value('category_id');

            $data = ['player_id' => $player_id, 'category_id' => $category_id];
            /*
            \Log::info(print_r($bid_session,true));
            \Log::info(json_encode($bid_session));
            \Log::info("player_id: " . print_r($player_id,true));
            \Log::info("category_id: " . print_r($category_id,true));
            \Log::info("data: " . print_r($data,true));
            */

            \Log::info(print_r($data,true));

           

         

            $result = UnsoldPlayer::create($data);

            \Log::info("*********023****");
            if ($result) {
                \Log::info("*********025****");
                // Update bid_sessions status to 'closed'
                BidSession::where('id',$session_id)->update(['status'=>'closed']);                
                // Update bids table to set is_winner for the highest bid
                Bid::where('id',$bid_id)->update(['is_winner'=>'1']);
                // Prepare success response
                return response()->json([
                    'success' => true,
                    'status' => 'success',
                    'bidding_url' => route('bidding.index'),
                    'message' => __('Successfully added the bid!'),           
                ],200);
            } else {
                \Log::info("*********024****");
                // Prepare error response if insertion failed                
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => __('Cancelled'),           
                ],409);
            }
        }

        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => __('Cancelled, Team id not found'),           
        ],409);

    }

    private function get_columns()
    {
        // Define column names (localized)
        $columns = [];
        //$columns['id'] = __('ID');
        $columns['sr'] = __('Sr.');
        $columns['league_name'] = __('League Name');
        $columns['player_name'] = __('Player Name');
        $columns['formatted_start_time'] = __('Start Time');
        $columns['formatted_end_time'] = __('End Time');
        $columns['formatted_status'] = __('Status');
        $columns['bid_actions'] = __('Actions');

        return $columns;
    }

    private function get_response()
    {
        $res = [];
        $res['success'] = false;
        $res['message'] = false;
        $res['errors'] = false;
        $res['statusCode'] = false;
        return $res;
    }
}
