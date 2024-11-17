<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

use Illuminate\Support\Facades\Log;

class CategoryApiController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request){        

        $category_name = $request->category_name;

        if(!empty($category_name)){
            // Check if the category already exists manually (Optional if you want to customize the message further)
            $existingCategory = Category::where('category_name', $request->category_name)->first();
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
        ],[
            'category_name.required' => 'Category name is required!',
            'category_name.unique' => 'This category name is already taken!',
        ]);         

        $color_code = $request->color_code == "#000001" ? NULL :  $request->color_code;
        
        try {
            $result = Category::create([
                'category_name'=> $request->category_name,
                'base_price'=> $request->base_price,
                'description'=> $request->description,            
                'color_code'=> $color_code,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
                'category' => array(),
            ]);

        } catch(Exception $e) {
           
            return response()->json([
                'success' => false,
                'message' => __('Category name already exists.'),
                'errors' => [
                    'category_name' => [$e->getMessage()]
                ]
            ], 409);
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $categories = Category::where('category_name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->get();

        return response()->json($categories);
    }

    public function refresh()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:150',
            'base_price' => 'required|numeric',
            'description' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:10',
        ]);

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'category' => $category,
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.',
        ]);
    }
}
