<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;

use App\Services\ImageUploadService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\SponsorRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class SponsorApiController extends Controller
{   

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('query', '');

        // Start the query builder for the Sponsor model
        $itemQuery = Sponsor::with([]); // Eager load the Sponsor relationship
        
        // If there is a search query, apply the filters
        if ($query) {
            $itemQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('sponsor_name', 'like', '%' . $query . '%')
                ->orWhere('sponsor_logo', 'like', '%' . $query . '%')
                ->orWhere('sponsor_description', 'like', '%' . $query . '%')
                ->orWhere('sponsor_website_url', 'like', '%' . $query . '%')
                ->orWhere('sponsor_type', 'like', '%' . $query . '%')
                ->orWhereHas('sponsor_type', function ($sponsorTypeQuery) use ($query) {
                    $sponsorTypeQuery->where('name', 'like', '%' . $query . '%');
                });
            });
        }

        $itemQuery->where('status', 'publish');

        // Order by category_name in ascending order
        $itemQuery->orderBy('sponsor_name', 'asc');

        $list_per_page = intval(setting('list_per_page', 10));

        // Paginate the results
        $items = $itemQuery->paginate($list_per_page);   
        
        //sponsor_type

        foreach($items as $key => $item){
            $item->sponsor_type_name = "";
        }

        // Return the columns and items data in JSON format
        return response()->json($this->getActionPermissionsAndColumns($items));
    }

    public function store(SponsorRequest $request){

        $request->validated();

        $formData = $request->all();

        $formData['status'] = $request->input('status', 'publish');
        $formData['created_by'] = Auth::id();

        if ($request->hasFile('sponsor_logo')) {
            //$id = $result->id;
            $filename = "";
            $uploadedFile = $request->file('sponsor_logo');
            //$filename = "sponsor_{$id}.jpg";
            $filename = "";
            $filename = $this->imageService->uploadImageWithThumbnail($uploadedFile,'sponsors','','',$filename);
            $formData['sponsor_logo'] = $filename;
            //$result->update(['sponsor_logo'=>$filename]);
        }

        try{

            // Create a new record
            $result = Sponsor::create($formData);

            return response()->json([
                'success' => true,
                'message' => 'Sponsor added successfully.',
                'data' => array(),
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => __('Sponsor not added.'),
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
            $item = Sponsor::select('sponsor_name', 'sponsor_description','sponsor_website_url','sponsor_type')->find($id);

            if ($item) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sponsor successfully found.',
                    'data' => $item,
                ], 200);
            } else {
                $res['errors'] = ['sponsor' => [__('Sponsor not found.')]];
                $res['message'] = __('An unexpected error occurred.');
                $res['statusCode'] = 404;
                return jsonResponse($res);
            }
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['sponsor' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['sponsor' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function update(SponsorRequest $request, $id)
    {
        $res = $this->get_response();

        $request->validated();

        $item = Sponsor::findOrFail($id);

        try {

            $formData = $request->all(); 
            
            if ($request->hasFile('sponsor_logo')) {

                $sponsor_logo = $item->sponsor_logo;
                
                $uploadedFile = $request->file('sponsor_logo');

                //$filename = "sponsor_{$id}.jpg"; 
                $filename = ""; 

                $filename = $this->imageService->uploadImageWithThumbnail($uploadedFile,'sponsors','','',$filename);              

                $formData['sponsor_logo'] = $filename;
               
                if($sponsor_logo){
                    $this->imageService->deleteMainFile($sponsor_logo,'sponsors');
                }               
            }else{
                unset($formData['sponsor_logo']);
            }
            
            $formData['status'] = $request->input('status', 'publish');
            $formData['updated_by'] = Auth::id(); // Current authenticated user ID

            // Update the record
            $item->update($formData);

            return response()->json([
                'success' => true,
                'message' => 'Sponsor updated successfully.',
                'data' => $item,
            ]);

            $res['success'] = true;
            $res['message'] = __('Sponsor updated successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['sponsor' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['sponsor' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function destroy($id)
    {
        $res = $this->get_response();

        try {
            $item = Sponsor::findOrFail($id); // Attempt to find the category by ID
            $item->delete(); // Delete the category if found
            $res['success'] = true;
            $res['message'] = __('Sponsor deleted successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['Sponsor not found' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['sponsor_delete' => [$e->getMessage()]];
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
        $columns['sponsor_logo'] = __('Logo');
        $columns['sponsor_name'] = __('Name');        
        $columns['sponsor_description'] = __('Description');
        $columns['sponsor_type_name'] = __('Type');
        $columns['actions'] = __('Actions');

        return $columns;
    }

    private function getActionPermissionsAndColumns($items){
        $columns = $this->get_columns();
        $user = auth()->user(); // Get the logged-in user

        // Get permissions for the actions
        $actions = [];        
        $actions['edit'] = $user->can('sponsor-edit');
        $actions['delete'] = $user->can('sponsor-delete');        

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
