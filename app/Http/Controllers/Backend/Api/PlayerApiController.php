<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class PlayerApiController extends Controller
{
    function get_columns()
    {
        // Define column names (localized)
        $columns = [];
        $columns['id'] = __('ID');
        $columns['player_name'] = __('Category Name');
        $columns['image'] = __('Unique Id');
        $columns['color_code'] = __('Profile');
        $columns['nickname'] = __('Name');
        $columns['profile_type'] = __('Profile Type');
        $columns['type'] = __('Type');
        $columns['style'] = __('Style');
        $columns['dob'] = __('Age');
        $columns['category_id'] = __('Category');        
        $columns['actions'] = __('Actions');

        return $columns;
    }

    public function index(Request $request)
    {
        
        

        // Get the search query from the request
        $query = $request->input('query', '');

        // Start the query builder for the Category model
        $itemQuery = Player::query();

        // If there is a search query, apply the filters
        if ($query) {
            $itemQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('category_name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('base_price', 'like', '%' . $query . '%')
                    ->orWhere('color_code', 'like', '%' . $query . '%');
            });
        }

        $itemQuery->where('status', 'publish');

        // Order by category_name in ascending order
        $itemQuery->orderBy('player_name', 'asc');
        //$categoriesQuery->orderBy('id', 'desc');

        // Paginate the results
        $items = $itemQuery->paginate(10);

        $columns = $this->get_columns();

        // Return the columns and items data in JSON format
        return response()->json([
            'columns' => $columns,
            'items' => $items
        ]);
    }

    public function store(Request $request)
    {

        try {

            // Validate the image
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


            //\Log::info('Request Object:', ['data' => json_encode($request)]);

            $status = $request->input('status', 'publish');

            // Current authenticated user ID
            $userId = Auth::id();

            \Log::info(json_encode($request->file('image')));

            $image_path = "";

            // Store the uploaded image
            if ($request->file('image')) {

                $image_path = $request->file('image')->store('images', 'public');

                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'image_path' => $image_path,
                ], 200);

               
            }

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'image_path' => $image_path,
            ], 200);

            $formData = [
                'player_name' => $request->player_name,
                'image' => $request->image,
                
                'profile_type' => $request->profile_type,
                'type' => $request->type,
                'style' => $request->style,
                'dob' => $request->dob,

                'category_id' => $request->category,
                'nickname' => $request->nick_name,
                'last_played_league' => $request->last_played_league,
                'address' => $request->address,

                'city' => $request->city,
                'email' => $request->email,

                'status' => $status,
                'created_by' => $userId,
            ];

            \Log::info(print_r($formData,true));

            $result = Player::create($formData);

            \Log::info(print_r($formData,true));

            return response()->json([
                'success' => true,
                'message' => 'Player added successfully.',
                'category' => array(),
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => __('Category name already exists.'),
                'errors' => [
                    'category_name' => [$e->getMessage()]
                ]
            ], 409);
        }
    }
}
