<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\{Role,Permission};

use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Http\Request;
use App\Http\Requests\UserRoleRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserRoleApiController extends Controller
{
    public function index(Request $request)
    {
       // Get the search query from the request
       $query = $request->input('query', '');        

       // Start the query builder for the Role model
       $itemQuery = Role::query();

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

       $columns = $this->get_columns();

       // Return the columns and items data in JSON format
       return response()->json([
           'columns' => $columns,
           'items' => $items
       ]);
    }

    public function store(UserRoleRequest $request)
    {       
        $request->validated();        

        try {           
           
            $formData = $request->all();

            $item = Role::create($formData);

            $item->syncPermissions($request->input('permission'));

            return response()->json([
                'success' => true,
                'message' => __('Role created successfully.'),
                'data' => $formData,
            ],201);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => __('Role name already exists.'),
                'errors' => [
                    'role_name' => [$e->getMessage()]
                ]
            ], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        // Select specific fields
        // Find by ID       

        try {

            $item = Role::select('name')->find($id);

            $permission = Permission::get();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                ->all();

               // \Log::info(\json_encode($rolePermissions));

            if ($item) {
                return response()->json([
                    'success' => true,
                    'message' => 'Role successfully found.',
                    'data' => $item,
                    'permission' => $permission,
                    'rolePermissions' => $rolePermissions,
                ], 200);
            } else {
                $res['errors'] = ['role' => [__('Role not found.')]];
                $res['message'] = __('An unexpected error occurred.');
                $res['statusCode'] = 404;
                return jsonResponse($res);
            }
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['role' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['role' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function update(UserRoleRequest $request, $id)
    {

       //info($id);
        //\Log::info("update user");

        $res = $this->get_response(); 
        
        $request->validated();

        try {          

            $data = $request->all();

            //\Log::info(print_r($data,true));

            $item = Role::find($id);

            // Update the record
            $item->update($data);

            $item->syncPermissions($request->input('permission'));

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully.',
                'role' => $data,
            ]);

            $res['success'] = true;
            $res['message'] = __('Role updated successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['Role not found' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['role_delete' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function destroy($id)
    {
        $res = $this->get_response();
        try {            
            $item = Role::findOrFail($id); // Attempt to find the role by ID

            // Check if the role is assigned to any user
            if ($item->users()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => __('This role cannot be deleted because it is assigned to one or more users.'),
                    'errors' => ['role_delete' => [__('This role cannot be deleted because it is assigned to one or more users.')]],
                ], 400); // Return a bad request status
            }

            // Proceed with deletion if not assigned
            $item->delete();

            $res['success'] = true;
            $res['message'] = __('Role deleted successfully');
            $res['statusCode'] = 200;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['role_delete' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['role_delete' => [$e->getMessage()]];
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
        $columns['name'] = __('Role Name');
        $columns['user_actions'] = __('Actions');
        return $columns;
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
