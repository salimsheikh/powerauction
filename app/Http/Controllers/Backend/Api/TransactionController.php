<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Team;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    function get_columns()
    {
        // Define column names (localized)
        $columns = [];
        //$columns['id'] = __('ID');
        $columns['sr'] = __('Sr.');
        $columns['category_name'] = __('Category Name');
        $columns['base_price'] = __('Base Price');
        $columns['color_code'] = __('Color Code');
        $columns['description'] = __('Description');
        $columns['actions'] = __('Actions');

        return $columns;
    }

    public function index(Request $request)
    {
        
    }

    public function store(Request $request){
        
        $validator = Validator::make($request->all(), [
            'plan_type' => 'required|string',
            'plan_amount' => 'required|numeric|gt:0',
        ],[
            'plan_type' => 'Plan Type is required.',
            'plan_amount' => 'Virtual Point is required.'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }        

        $formData = $request->all();

        $formData['user_id'] = Auth::id();
        $formData['team_id'] = $request->input('team_id', '0');
        $formData['plan_id'] = $request->input('plan_id', '0');
        $formData['type'] = $request->input('type', 'credit');
        $formData['amount'] = $request->input('plan_amount', '0');
        
        $formData['status'] = $request->input('status', 'publish');
        $formData['created_by'] = Auth::id();

        Log::info($formData);

        try{

            // Create a new record
            $result = Transaction::create($formData);

             // Update the account balance
            $team = Team::find($request->team_id);
            if($team){
                $team->virtual_point += $request->plan_amount;
                $team->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction added successfully.',
                'data' => array(),
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => __('Transaction not added.'),
                'errors' => [
                    'error' => [$e->getMessage()]
                ]
            ], 409);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }
}
