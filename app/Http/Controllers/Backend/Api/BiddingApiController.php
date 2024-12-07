<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BidSession;
use Carbon\Carbon;

use App\Models\Team;
use App\Models\SoldPlayer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BiddingApiController extends Controller
{
    public function index(Request $request){
        // Get the search query from the request
        $query = $request->input('query', '');        

        // Start the query builder for the Category model
        $itemQuery = BidSession::query()->with(['league','players']);

        // If there is a search query, apply the filters
        if ($query) {
            $itemQuery->where(function ($queryBuilder) use ($query) {

                $queryBuilder->where('start_time', 'like', '%' . $query . '%')
                    ->orWhere('end_time', 'like', '%' . $query . '%')
                    ->orWhere('status', 'like', '%' . $query . '%');

                $queryBuilder->orWhereHas('league', function ($subQuery) use ($query) {
                    $subQuery->where('league_name', 'like', '%' . $query . '%');
                });

                $queryBuilder->orWhereHas('players', function ($subQuery) use ($query) {
                    $subQuery->where('player_name', 'like', '%' . $query . '%');
                });
            });
        }

        // $itemQuery->where('status', 'publish');

        // Order by category_name in ascending order
        $itemQuery->orderBy('created_at', 'desc');

        // Paginate the results
        $items = $itemQuery->paginate(10);
        
    
        foreach($items as $key => $item){
            $items[$key]->league_name = $item->league?->league_name;
            $items[$key]->player_name = $item->players?->player_name;

            $items[$key]->formatted_start_time = $item->formatted_start_time;
            $items[$key]->formatted_end_time = $item->formatted_end_time;      
            $items[$key]->formatted_status = "";
            
            
        }

        $columns = $this->get_columns();

        // Return the columns and items data in JSON format
        return response()->json([
            'columns' => $columns,
            'items' => $items
        ]);
    }


    public function startBidding(Request $request){
        $userId = Auth::id();

        $expire_minutes = intval(config('app.auction_expire_minutes', 2));
        
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

        $team_id = $request->input('team_id');
        $session_id = $request->input('session_id');
        if($team_id > 0){
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

            \Log::info("virtual_point:- {$virtual_point}");
            \Log::info("purchased_point:- {$purchased_point}");
            \Log::info("remaining_point:- {$remaining_point}");
            \Log::info("enter_amount:- {$enter_amount}");

            // Check if the remaining points are greater than or equal to the amount specified in the post request
            if ($remaining_point < (int)$enter_amount) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => __('Cancelled 1.'),
                    'logic' => 1,
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
                    'message' => __('Cancelled 2.'),
                    'logic' => 2,
                    'errors' => [
                        'error' => [__('Cancelled.')]
                    ]
                ], 409);
            }

            $playerData = DB::table('bid_sessions as s')
            ->select('s.player_id', 'p.category_id', 'c.base_price')
            ->where('s.id',  $session_id)
            ->join('players as p', 'p.id', '=', 's.player_id')
            ->join('categories as c', 'c.id', '=', 'p.category_id')
            ->first();

            \Log::info(print_r($playerData,true));

            $base_price = isset($player_data['base_price']) ? $player_data['base_price'] : 0;

            //$player_data = $this->db->select('s.player_id, p.category_id, c.base_price')->from('bid_sessions as s')->where('s.session_id', $this->input->post('session_id'))->join('players as p', 'p.players_id = s.player_id')->join('category as c', 'c.category_id = p.category_id')->get()->row_array();
            
        }       

        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => __('Successfully Edited!.'),           
        ],201);

        /*
        $player_data = $this->db->select('s.player_id, p.category_id, c.base_price')->from('bid_sessions as s')->where('s.session_id', $this->input->post('session_id'))->join('players as p', 'p.players_id = s.player_id')->join('category as c', 'c.category_id = p.category_id')->get()->row_array();
        if ($player_data['base_price'] > (int)$this->input->post('amount')) {
            $response = ['status' => 'error', 'message' => 'Cancelled 2'];
            // Return response as JSON
            echo json_encode($response);
            exit();
        }
        $data['session_id'] = $this->input->post('session_id');
        $data['owner_id'] = $this->session->userdata('admin_id');
        $data['team_id'] = $this->input->post('team_id');
        $data['amount'] = $this->input->post('amount');
        $data['bid_time'] = date('Y-m-d H:i:s');
        if ($this->db->insert('bids', $data)) {
            $id = $this->db->insert_id();
            $response = ['status' => 'success', 'bid_id' => $id, 'message' => 'Successfully added the bid!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Cancelled 4'];
        }
        // Return the response as JSON
        echo json_encode($response);
        */
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
