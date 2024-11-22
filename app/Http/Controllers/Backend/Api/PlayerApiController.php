<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class PlayerApiController extends Controller
{
    function get_columns()
    {
        // Define column names (localized)
        $columns = [];
        $columns['id'] = __('ID');
        $columns['category_name'] = __('Category Name');
        $columns['base_price'] = __('Base Price');
        $columns['color_code'] = __('Color Code');
        $columns['description'] = __('Description');
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

        //    $itemQuery->where('status', 'publish');

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

        $category_name = $request->category_name;

        if (!empty($category_name)) {
            // Check if the category already exists manually (Optional if you want to customize the message further)
            $existingCategory = Player::where('category_name', $request->category_name)->first();
            if ($existingCategory) {
                // Return a custom error response for duplicate category_name
                return response()->json([
                    'success' => false,
                    'message' => __('Category name already exists.'),
                    'errors' => [
                        'category_name' => [__('The category name has already been taken.')]
                    ]
                ], 409);  // 409 Conflict - used when the request could not be completed due to a conflict.
            }
        }

        // Validate the request
        $validated = $request->validate([
            'category_name' => 'required|string|max:150|unique:categories,category_name',
            'base_price' => 'required|numeric',
            'description' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:10',
        ], [
            'category_name.required' => 'Category name is required!',
            'category_name.unique' => 'This category name is already taken!',
        ]);

        $color_code = $request->color_code == "#000001" ? NULL :  $request->color_code;

        try {

            $status = $request->input('status', 'publish');

            // Current authenticated user ID
            $userId = Auth::id();

            $result = Player::create([
                'category_name' => $request->category_name,
                'base_price' => $request->base_price,
                'description' => $request->description,
                'color_code' => $color_code,
                'status' => $status,
                'created_by' => $userId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
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
