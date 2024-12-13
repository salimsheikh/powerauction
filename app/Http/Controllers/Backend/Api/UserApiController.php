<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\{Role,Permission};

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserApiController extends Controller
{
    public function index(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('query', '');
        // Start the query builder for the User model
        $itemQuery = User::query()
        ->select(['id', 'name', 'address', 'email', 'phone']) // Select only necessary columns
        ->with(['roles:id, name']); // Eager load roles with only 'id' and 'name'     

       // Start the query builder for the User model
       $itemQuery = User::query()->with('roles');

       // If there is a search query, apply the filters
       if ($query) {
            $itemQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('phone', 'like', '%' . $query . '%')
                    ->orWhere('address', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhereHas('roles', function ($roleQuery) use ($query) {
                        $roleQuery->where('name', 'like', '%' . $query . '%');
                    });
            });
        }    
       
        $list_per_page = intval(setting('list_per_page',10) ?? 10);

        $itemQuery->orderBy('name', 'asc'); 

        // Paginate the results
        $items = $itemQuery->paginate($list_per_page);        

        foreach ($items as $user) {
            $user->user_roles = $user->roles->pluck('name')->implode(',');
        }

        // Return the columns and items data in JSON format
        return response()->json($this->getActionPermissionsAndColumns($items));
    }

    public function store(UserRequest $request)
    {       
        $request->validated();        

        try {           
            
            $formData = $request->only(['name', 'phone', 'address', 'email']); // Whitelist allowed fields

            // Hash the password if provided
            if ($request->filled('password')) {
                $formData['password'] = Hash::make($request->password);
            }       
            
            $formData['created_by'] = Auth::id();

            $item = User::create($formData);
            $item->assignRole($request->input('roles'));

            return response()->json([
                'success' => true,
                'message' => __('User created successfully.'),
                'data' => $formData,
            ],201);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => __('User name already exists.'),
                'errors' => [
                    'user_name' => [$e->getMessage()]
                ]
            ], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        // Select specific fields
        // Find by ID       

        try {

            $item = User::select('id','name', 'phone', 'address', 'email')->find($id);
            
            $roles = Role::pluck('id','name')->all();           

            $userRole = $item->roles->pluck('id','id')->all();              
            

            if ($item) {
                return response()->json([
                    'success' => true,
                    'message' => 'User successfully found.',
                    'data' => $item,
                    'rolePermissions' => $userRole
                ], 200);
            } else {
                $res['errors'] = ['user' => [__('User not found.')]];
                $res['message'] = __('An unexpected error occurred.');
                $res['statusCode'] = 404;
                return jsonResponse($res);
            }
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['user' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['user' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function update(UserRequest $request, $id)
    {
        $res = $this->get_response(); 
        
        $request->validated();

        // Begin a database transaction
        DB::beginTransaction();

        try {

            $data = $request->only(['name', 'phone', 'address', 'email']); // Whitelist allowed fields

            // Hash the password if provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
    
            // Add the updated_by field
            $data['updated_by'] = Auth::id();
    
            // Find the user
            $item = User::findOrFail($id);
    
            // Update the record
            $item->update($data);
         
            DB::table('model_has_roles')->where('model_id',$id)->delete();
        
            $item->assignRole($request->input('roles'));

            // Commit the transaction
            DB::commit();    

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'user' => $data,
            ]);

            $res['success'] = true;
            $res['message'] = __('User updated successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {

            // Rollback the transaction on error
            DB::rollBack();

            $res['errors'] = ['User not found' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {

            // Rollback the transaction on error
            DB::rollBack();

            $res['errors'] = ['user_delete' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function destroy($id)
    {
        $res = $this->get_response();

        try {
            $item = User::findOrFail($id); // Attempt to find the user by ID
            $item->delete(); // Delete the user if found
            $res['success'] = true;
            $res['message'] = __('User deleted successfully');
            $res['statusCode'] = 200;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['User not found' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['user_delete' => [$e->getMessage()]];
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
        $columns['name'] = __('Name');
        $columns['phone'] = __('Phone');
        $columns['address'] = __('Address');
        $columns['email'] = __('Email');
        $columns['user_roles'] = __('Roles');
        
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
