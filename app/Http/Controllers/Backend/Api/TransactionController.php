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
        $columns['sr'] = __('No.');
        $columns['plan_id'] = __('Plan Name');
        $columns['amount'] = __('Virtual Amount');
        $columns['created_by'] = __('Date');

        return $columns;
    }

    public function index(Request $request, $team_id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Transaction data.',
            'data' => array(),
            'transactions' => $this->getList($team_id)
        ]);
    }

    function getList($team_id){
        $items = Transaction::with('plan:name,id') // Load only the name and id from the plan table
        ->where('team_id', $team_id) // Replace $teamId with the desired team ID
        ->select('id', 'amount', 'created_at', 'plan_id', 'team_id')
        ->latest('created_at')
        ->get()
        ->map(function ($transaction) {
            return [
                'name' => $transaction->plan->name,
                'amount' => $transaction->amount,
                'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return $items;
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
        $formData['plan_id'] = $request->input('plan_type', '0');
        $formData['type'] = $request->input('type', 'credit');
        $formData['amount'] = $request->input('plan_amount', '0');
        
        $formData['status'] = $request->input('status', 'publish');
        $formData['created_by'] = Auth::id();

        Log::info($formData);

        try{

            // Create a new record
            $result = Transaction::create($formData);

            $team_id = $request->team_id;

             // Update the account balance
            $team = Team::find($team_id);
            if($team){
                $team->virtual_point += $request->plan_amount;
                $team->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction added successfully.',
                'data' => $result,
                'transactions' => $this->getList($team_id)
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
    }
}
