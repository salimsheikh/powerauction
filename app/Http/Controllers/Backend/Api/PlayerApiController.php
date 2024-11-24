<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Services\ImageUploadService;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PlayerApiController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    function get_columns()
    {
        // Define column names (localized)
        $columns = [];
        $columns['id'] = __('ID');
        $columns['uniq_id'] = __('Unique Id');
        $columns['image'] = __('Profile');
        $columns['player_nickname'] = __('Name');
        $columns['profile_type_label'] = __('Profile Type');
        $columns['type_label'] = __('Type');
        $columns['style_label'] = __('Style');
        $columns['age'] = __('Age');
        $columns['category_name'] = __('Category');        
        $columns['actions'] = __('Actions');

        return $columns;
    }

    public function index(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('query', '');

        // Start the query builder for the Category model
        // Start the query builder for the Player model
        $itemQuery = Player::with([]); // Eager load the category relationship

        // If there is a search query, apply the filters
        if ($query) {
            $itemQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('player_name', 'like', '%' . $query . '%')
                    ->orWhere('nickname', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($query) {
                        $categoryQuery->where('category_name', 'like', '%' . $query . '%');
                    });
            });
        }

        $itemQuery->where('status', 'publish');

        // Order by category_name in ascending order
        $itemQuery->orderBy('created_at', 'desc');

        // Paginate the results
        $items = $itemQuery->paginate(10);

        foreach($items as $key => $item){
            $items[$key]->category_name = '';
            $items[$key]->age = '';
            $items[$key]->player_nickname = '';
            $items[$key]->profile_type_label = '';
            $items[$key]->type_label = '';
            $items[$key]->style_label = '';

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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:6000',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $filename = "";

        if ($request->hasFile('image')) {
             // Get the uploaded file
            $uploadedFile = $request->file('image');
            
            $filename = $this->imageService->uploadImageWithThumbnail($uploadedFile,'players');
        }

        $status = $request->input('status', 'publish');

        // Current authenticated user ID
        $userId = Auth::id();

        // $imagePath = Storage::url($imagePath);

        $dateInput = $request->dob;
        $formattedDate = $request->dob;

        // Use DateTime to create a date object and format it as yyyy-mm-dd
       // $formattedDate = \DateTime::createFromFormat('d-m-Y', $dateInput)->format('Y-m-d');

       // Replace the first '-' with a character that splits day, month, and year
        $formattedDate = Str::replaceFirst('-', '', $dateInput); // e.g., '251124' becomes '25-11-2024'
        $formattedDate = Str::replaceFirst('-', '', $formattedDate); // transforms to '2024-11-25'


        \Log::info('Formatted Date: '.$dateInput . "  - - -" . $formattedDate);

        $formData = [
            'player_name' => $request->player_name,
            'image' => $filename,
            'image_thumb' => $filename,
            
            'profile_type' => $request->profile_type,
            'type' => $request->type,
            'style' => $request->style,
            'dob' => $formattedDate,

            'category_id' => $request->category,
            'nickname' => $request->nick_name,
            'last_played_league' => $request->last_played_league,
            'address' => $request->address,

            'city' => $request->city,
            'email' => $request->email,

            'status' => $status,
            'created_by' => $userId,
        ];

        // \Log::info($formData);  

        try{

            // Create a new record
            $result = Player::create($formData);

            // Get the inserted record's ID
            $insertedId = $result->id;

            // Generate a unique ID (for example, using a UUID)
            $uniqueId = "SPL/".$insertedId;

            // Update the record with the unique ID
            $result->update(['uniq_id' => $uniqueId]);

            return response()->json([
                'success' => true,
                'message' => 'Player added successfully.',
                'category' => array(),
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => __('Player not added.'),
                'errors' => [
                    'category_name' => [$e->getMessage()]
                ]
            ], 409);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }

    public function edit(Request $request, $id)
    {
        try {

            $item = Player::select('category_name', 'color_code', 'base_price', 'description')->find($id);
            if ($item) {
                return response()->json([
                    'success' => true,
                    'message' => 'Player successfully found.',
                    'data' => $item,
                ], 200);
            } else {
                $res['errors'] = ['player' => [__('Player not found.')]];
                $res['message'] = __('An unexpected error occurred.');
                $res['statusCode'] = 404;
                return jsonResponse($res);
            }
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['player' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['player' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function destroy($id)
    {
        $res = $this->get_response();

        $item = Player::select('image')->find($id);

        if ($item) {
            $image = $item->image; // Access the 'image' value            
            $this->imageService->deleteSavedFile($image, 'players');
        }

        try {
            $item = Player::findOrFail($id); // Attempt to find the category by ID
            $item->delete(); // Delete the category if found
            $res['success'] = true;
            $res['message'] = __('Player deleted successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['Player not found' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['player_delete' => [$e->getMessage()]];
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
}
