<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Services\ImageUploadService;

class SponsorApiController extends Controller
{
    function get_columns()
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
        $itemQuery->orderBy('created_at', 'desc');

        // Paginate the results
        $items = $itemQuery->paginate(10);   
        
        //sponsor_type

        foreach($items as $key => $item){
            $item->sponsor_type_name = "";
        }


        $columns = $this->get_columns();

        // Return the columns and items data in JSON format
        return response()->json([
            'columns' => $columns,
            'items' => $items
        ]);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'sponsor_name' => 'required|string|max:100|unique:sponsors,sponsor_name',
            'sponsor_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',      
            'sponsor_description' => 'required|string|max:500',
            'sponsor_website_url' => 'required|string|max:200',
            'sponsor_type' => 'required|string|max:10'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $formData = $request->all();        

        if ($request->hasFile('sponsor_logo')) {
            $uploadedFile = $request->file('sponsor_logo');               
            $filename = $this->imageService->uploadImageWithThumbnail($uploadedFile,'sponsors');
            $formData['sponsor_logo'] = $filename;
        }

        $formData['status'] = $request->input('status', 'publish');
        $formData['created_by'] = Auth::id();       

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

    public function update(Request $request, $id)
    {
        $res = $this->get_response();

        $validator = Validator::make($request->all(), [
            'sponsor_name' => 'required|string|max:100|unique:sponsors,sponsor_name,'.$id,
            'sponsor_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',      
            'sponsor_description' => 'required|string|max:500',
            'sponsor_website_url' => 'required|string|max:200',
            'sponsor_type' => 'required|string|max:10'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $item = Sponsor::findOrFail($id);

        try {

            $formData = $request->all(); 
            
            if ($request->hasFile('sponsor_logo')) {

                $sponsor_logo = $item->sponsor_logo;
                
                $uploadedFile = $request->file('sponsor_logo');

                $filename = $this->imageService->uploadImageWithThumbnail($uploadedFile,'sponsors');              

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

    function get_response()
    {
        $res = [];
        $res['success'] = false;
        $res['message'] = false;
        $res['errors'] = false;
        $res['statusCode'] = false;
        return $res;
    }

    function get_formated_date($dateInput = ''){
        if($dateInput != ""){
            // Replace the first '-' with a character that splits day, month, and year
            $formattedDate = Str::replaceFirst('-', '', $dateInput); // e.g., '251124' becomes '25-11-2024'
            $formattedDate = Str::replaceFirst('-', '', $formattedDate); // transforms to '2024-11-25'  
            return $formattedDate;
        }
        return $dateInput;
    }
}
