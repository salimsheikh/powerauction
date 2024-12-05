<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BidSession;
use Carbon\Carbon;
use Log;

use Illuminate\Support\Facades\Auth;

class BiddingApiController extends Controller
{
    public function startBidding(Request $request){
        $userId = Auth::id();

        $expire_minutes = intval(config('app.auction_expire_minutes', 2));
        Log::info($expire_minutes);

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
}
