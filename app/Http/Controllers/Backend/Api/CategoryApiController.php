<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CategoryApiController extends Controller
{
    public function index(Request $request)
    {
        $columns = [];
        $columns['id'] = __('ID');
        $columns['category_name'] = __('category_name');
        $columns['color_code'] = __('color_code');
        $columns['base_price'] = __('base_price');
        $columns['description'] = __('description');

        $categories = Category::select('id','category_name','color_code','base_price', 'description')->paginate(5);
        return response()->json([
            'columns' => $columns,
            'categories' => $categories
        ]);
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

    public function edit(Request $request, $id)
    {
        // Select specific fields
        // Find by ID       
        
        try {

            $category = Category::select('category_name','color_code','base_price', 'description')->find($id);
            if($category){
                return response()->json([
                    'success' => true,
                    'message' => 'Category successfully found.',
                    'data' => $category,
                ],200);
            }else{
                $res['errors'] = ['category' => [__('Category not found.')]];
                $res['message'] = __('An unexpected error occurred.');
                $res['statusCode'] = 404;
                return jsonResponse($res);
            }
            
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['category' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['category' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function update(Request $request, $id)
    {
        $res = [];
        $res['success'] = false;
        $res['message'] = false;
        $res['errors'] = false;
        $res['statusCode'] = false;

        $category_name = $request->category_name;
        
        if(!empty($category_name)){
            // Check if the category already exists manually (Optional if you want to customize the message further)
            $existingCategory = Category::where('category_name', $request->category_name)
            ->where('id', '!=', $id) // Exclude the given ID
            ->first();
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

        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:150',
            'base_price' => 'required|numeric',
            'description' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:10',
        ]);       

        try {

            $category->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
                'category' => $category,
            ]);

            $res['success'] = true;
            $res['message'] = __('Category updated successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['Category not found' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['category_delete' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function destroy($id)
    {
        $res = [];
        $res['success'] = false;
        $res['message'] = false;
        $res['errors'] = false;
        $res['statusCode'] = false;

        try {
            $category = Category::findOrFail($id); // Attempt to find the category by ID
            $category->delete(); // Delete the category if found
            $res['success'] = true;
            $res['message'] = __('Category deleted successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['Category not found' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['category_delete' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }

       
    }
}
