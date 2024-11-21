<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;

class PlayerApiController extends Controller
{
    function get_columns(){
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
       $query = $request->input('query','');

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
}
