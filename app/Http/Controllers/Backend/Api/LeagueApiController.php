<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\League;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeagueApiController extends Controller
{
    function get_columns()
    {
        // Define column names (localized)
        $columns = [];
        $columns['sr'] = __('Sr.');
        $columns['league_name'] = __('Name');
        $columns['description'] = __('Description');
        $columns['actions'] = __('Actions');

        return $columns;
    }

    public function index(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('query', '');

        // Start the query builder for the Player model
        $itemQuery = League::with([]); // Eager load the Player relationship

        // If there is a search query, apply the filters
        if ($query) {
            $itemQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('league_name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
            });
        }

        $itemQuery->where('status', '1');

        // Order by category_name in ascending order
        $itemQuery->orderBy('created_at', 'desc');

        // Paginate the results
        $items = $itemQuery->paginate(10);      

        $columns = $this->get_columns();

        // Return the columns and items data in JSON format
        return response()->json([
            'columns' => $columns,
            'items' => $items
        ]);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'league_name' => 'required|string|max:100|unique:league,league_name',      
            'description' => 'required|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $formData = $request->all();
        
        $formData['status'] = $request->input('status', '1');
        $formData['created_by'] = Auth::id();       

        try{

            // Create a new record
            $result = League::create($formData);           

            return response()->json([
                'success' => true,
                'message' => 'League added successfully.',
                'data' => array(),
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => __('League not added.'),
                'errors' => [
                    'error' => [$e->getMessage()]
                ]
            ], 409);
        }   
    }

    

    public function edit(Request $request, $id)
    {
        $res = $this->get_response();

        try {
            $item = League::select('league_name', 'description')->find($id);

            if ($item) {
                return response()->json([
                    'success' => true,
                    'message' => 'League successfully found.',
                    'data' => $item,
                ], 200);
            } else {
                $res['errors'] = ['player' => [__('League not found.')]];
                $res['message'] = __('An unexpected error occurred.');
                $res['statusCode'] = 404;
                return jsonResponse($res);
            }
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['league' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['league' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function update(Request $request, $id)
    {
        $res = $this->get_response();

        $validator = Validator::make($request->all(), [
            'league_name' => 'required|string|max:100|unique:league,league_name,' . $id,
            'description' => 'required|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $item = League::findOrFail($id);

        try {

            $formData = $request->all();            
            
            $formData['status'] = $request->input('status', '1');;
            $formData['updated_by'] = Auth::id(); // Current authenticated user ID

            // Update the record
            $item->update($formData);

            return response()->json([
                'success' => true,
                'message' => 'Player updated successfully.',
                'data' => $item,
            ]);

            $res['success'] = true;
            $res['message'] = __('Player updated successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['player' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['player' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function destroy($id)
    {
        $res = $this->get_response();

        try {
            $item = League::findOrFail($id); // Attempt to find the category by ID
            $item->delete(); // Delete the category if found
            $res['success'] = true;
            $res['message'] = __('League deleted successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['League not found' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['league_delete' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    function get_response()
    {
        $res = [];
        $res['success'] = false;
        $res['message'] = false;
        $res['errors'] = false;
        $res['statusCode'] = false;
        return $res;
    }

    function get_formated_date($dateInput = ''){
        if($dateInput != ""){
            // Replace the first '-' with a character that splits day, month, and year
            $formattedDate = Str::replaceFirst('-', '', $dateInput); // e.g., '251124' becomes '25-11-2024'
            $formattedDate = Str::replaceFirst('-', '', $formattedDate); // transforms to '2024-11-25'  
            return $formattedDate;
        }
        return $dateInput;
    }
}