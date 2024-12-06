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
