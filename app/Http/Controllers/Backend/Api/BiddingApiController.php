<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BidSession;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

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

    function bid(){
        /*
        $team_point = $this->db->select('virtual_point')->where('teams_id', $this->input->post('team_id'))->get('teams')->row_array();
        $purchased_point = $this->db->select_sum('sold_price')->where('teams_id', $this->input->post('team_id'))->get('soldplayers')->row()->sold_price;
        $virtual_point = isset($team_point['virtual_point']) ? $team_point['virtual_point'] : 0;
        $purchased_point2 = $this->db->select_sum('sold_price')->where('teams_id', $this->input->post('team_id'))->get('soldplayers')->row();
        $remaining_point = $virtual_point - $purchased_point;
        // Check if the remaining points are greater than or equal to the amount specified in the post request
        if ($remaining_point < (int)$this->input->post('amount')) {
            $response = ['status' => 'error', 'message' => 'Cancelled 1', 'purchased_point2' => print_r($purchased_point2, true), 'purchased_point' => $purchased_point, 'virtual_point' => $virtual_point, 'remaining_point' => $remaining_point, 'team_id' => print_r($_POST, true), 'amount' => $this->input->post('amount') ];
            // Return response as JSON
            echo json_encode($response);
            exit();
        }
        if ($virtual_point < (int)$this->input->post('amount')) {
            $response = ['status' => 'error', 'message' => 'Cancelled 2 php', 'purchased_point' => $purchased_point, 'virtual_point' => $virtual_point, 'remaining_point' => $remaining_point, 'amount' => $this->input->post('amount') ];
            // Return response as JSON
            echo json_encode($response);
            exit();
        }
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
