<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

use Spatie\Permission\Models\{Role, Permission};
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\UserPermissionRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserPermissionController extends Controller
{
    public function index(Request $request)
    {
       // Get the search query from the request
       $query = $request->input('query', '');        

       // Start the query builder for the Permission model
       $itemQuery = Permission::query();

       // If there is a search query, apply the filters
       if ($query) {
           $itemQuery->where(function ($queryBuilder) use ($query) {
               $queryBuilder->where('name', 'like', '%' . $query . '%');
           });
       }       
       
       $list_per_page = intval(setting('list_per_page', 10));

       $itemQuery->orderBy('name', 'asc'); 

       // Paginate the results
       $items = $itemQuery->paginate($list_per_page);

       // Return the columns and items data in JSON format
       return response()->json($this->getActionPermissionsAndColumns($items));
    }

    public function store(UserPermissionRequest $request)
    {       
        $request->validated();        

        try {           
           
            $formData = $request->all();            

            $item = Permission::create($formData);

            return response()->json([
                'success' => true,
                'message' => __('Permission created successfully.'),
                'data' => $formData,
            ],201);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => __('Permission name already exists.'),
                'errors' => [
                    'permission_name' => [$e->getMessage()]
                ]
            ], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        // Select specific fields
        // Find by ID       

        try {

            $item = Permission::select('name')->find($id);

           

            if ($item) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permission successfully found.',
                    'data' => $item,                   
                ], 200);
            } else {
                $res['errors'] = ['permission' => [__('Permission not found.')]];
                $res['message'] = __('An unexpected error occurred.');
                $res['statusCode'] = 404;
                return jsonResponse($res);
            }
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['permission' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['permission' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function update(UserPermissionRequest $request, $id)
    {
        $res = $this->get_response(); 
        
        $request->validated();

        try {          

            $data = $request->all();

            $item = Permission::find($id);

            $data['name'] = Str::slug($data['name'], '-');

            // Update the record
            $item->update($data);           

            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully.',
                'permission' => $data,
            ]);

            $res['success'] = true;
            $res['message'] = __('Permission updated successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['Permission not found' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['permission_delete' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function destroy($id)
    {
        $res = $this->get_response();

        try {
            $item = Permission::findOrFail($id); // Attempt to find the permission by ID

             // Check if the role is assigned to any user
             if ($item->roles()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => __('This permission cannot be deleted because it is assigned to one or more roles.'),
                    'errors' => ['role_delete' => [__('This permission cannot be deleted because it is assigned to one or more roles.')]],
                ], 400); // Return a bad request status
            }

            $item->delete(); // Delete the permission if found
            $res['success'] = true;
            $res['message'] = __('Permission deleted successfully');
            $res['statusCode'] = 200;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['Permission not found' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['permission_delete' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    private function get_columns()
    {
        // Define column names (localized)
        $columns = [];
        $columns['sr'] = __('Sr.');
        $columns['name'] = __('Permission Name');
        $columns['user_actions'] = __('Actions');
        return $columns;
    }

    private function getActionPermissionsAndColumns($items){
        $columns = $this->get_columns();
        $user = auth()->user(); // Get the logged-in user

        // Get permissions for the actions
        $actions = [];        
        //$actions['edit'] = $user->can('category-edit');
        //$actions['delete'] = $user->can('category-delete');

        $actions['edit'] = true;
        $actions['delete'] = true;

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
