<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CategoryApiController extends Controller
{
    public function index(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('query', '');        

        // Start the query builder for the Category model
        $itemQuery = Category::query();

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
        $itemQuery->orderBy('category_name', 'asc'); 
        
        $list_per_page = intval(setting('list_per_page', 10));

        // Paginate the results
        $items = $itemQuery->paginate($list_per_page);        

        // Return the columns and items data in JSON format
        return response()->json($this->getActionPermissionsAndColumns($items));
    }    

    public function store(CategoryRequest $request)
    {       
        $request->validated();

        $color_code = $request->color_code == "#000001" ? NULL :  $request->color_code;

        try {           
            // Current authenticated user ID
            $userId = Auth::id();

            $formData = $request->all();

            $formData['color_code'] = $color_code;
            
            $formData['created_by'] = Auth::id();

            $result = Category::create($formData);

            return response()->json([
                'success' => true,
                'message' => __('Category created successfully.'),
                'data' => $formData,
            ],201);
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

    public function edit(Request $request, $id)
    {
        // Select specific fields
        // Find by ID       

        try {

            $category = Category::select('category_name', 'color_code', 'base_price', 'description')->find($id);

            if ($category) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category successfully found.',
                    'data' => $category,
                ], 200);
            } else {
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

    public function update(CategoryRequest $request, $id)
    {
        $res = $this->get_response(); 
        
        $request->validated();

        try {

            $color_code = $request->color_code == "#000001" ? NULL :  $request->color_code;

            $data = $request->all();

            $userId = Auth::id();

            $data['color_code'] = $color_code;

            // Update modified_by field
            $data['updated_by'] = $userId;

            $category = Category::find($id);

            // Update the record
            $category->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
                'category' => $data,
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
        $res = $this->get_response();

        try {
            $category = Category::findOrFail($id); // Attempt to find the category by ID
            $category->delete(); // Delete the category if found
            $res['success'] = true;
            $res['message'] = __('Category deleted successfully');
            $res['statusCode'] = 200;
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

    private function get_columns()
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

    private function getActionPermissionsAndColumns($items){
        $columns = $this->get_columns();
        $user = auth()->user(); // Get the logged-in user

        // Get permissions for the actions
        $actions = [];        
        $actions['edit'] = $user->can('category-edit');
        $actions['delete'] = $user->can('category-delete');

        // Exclude the actions column if no actions are allowed
        if (!$actions['edit'] && !$actions['delete']) {
            unset($columns['actions']);
        }

        return [
            'columns' => $columns,
            'items' => $items,
            'actions' => $actions
        ];
    }

    private function get_response()
    {
        $res = [];
        $res['success'] = false;
        $res['message'] = false;
        $res['errors'] = false;
        $res['statusCode'] = false;
        return $res;
    }
}
