<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BidSession;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class BiddingApiController extends Controller
{
    public function start_bidding(Request $request){

        $userId = Auth::id();

        $data = [];
        $data['league_id'] = $request->input('league_id');
        $data['player_id'] = $request->input('player_id');
        $data['start_time'] = now();
        $data['end_time'] = Carbon::now()->addMinutes(2)->format('Y-m-d H:i:s');
        $data['status'] = 'active';
        $data['created_by'] = $userId;       

        try {    
                   
            $result = BidSession::create($data);

            return response()->json([
                'success' => true,
                'message' => __('Bid Session created successfully.'),
                'data' => $formData,
            ],201);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => __('Bid Session already exists.'),
                'errors' => [
                    'error' => [$e->getMessage()]
                ]
            ], 409);
        }
    }
}
