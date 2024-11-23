<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class PlayerApiController extends Controller
{
    function get_columns()
    {
        // Define column names (localized)
        $columns = [];
        $columns['id'] = __('ID');
        $columns['player_name'] = __('Profile Name');
        $columns['image'] = __('Unique Id');
        $columns['color_code'] = __('Profile');
        $columns['nickname'] = __('Name');
        $columns['profile_type'] = __('Profile Type');
        $columns['type'] = __('Type');
        $columns['style'] = __('Style');
        $columns['dob'] = __('Age');
        $columns['category_id'] = __('Category');        
        $columns['actions'] = __('Actions');

        return $columns;
    }

    public function index(Request $request)
    {
        
        

        // Get the search query from the request
        $query = $request->input('query', '');

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

        $itemQuery->where('status', 'publish');

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

    public function store(Request $request){

        //\Log::info($request->all());  // Log all request data

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

        $imagePath = "";
        $thumbnailPath = "";

        if ($request->hasFile('image')) {

            // Get the uploaded file
            $uploadedFile = $request->file('image');


            // Players Image Direcotry
            $playerImagePath = storage_path('app/public/players/');

            // Players Image Direcotry
            $thumbImagePath = storage_path('app/public/players/thumbs/');

            // Check if the folder exists, if not, create it
            if (!File::exists($playerImagePath)) {
                File::makeDirectory($playerImagePath, 0777, true); // true to create subdirectories
            }
            
            // Check if the folder exists, if not, create it
            if (!File::exists($thumbImagePath)) {
                File::makeDirectory($thumbImagePath, 0777, true); // true to create subdirectories
            } 

            // Define the custom file name
            $filename = Str::random(6) . '_' .time() . '.jpg';

            /** Simple upload the file */
            //$imagePath = $uploadedFile->store('players', 'public');

             // Convert the image to JPG and resize it (optional)
            $largeImage = Image::make($uploadedFile->getPathname())->encode('jpg', 90); // Convert to JPG with 90% quality


             // Save the image to the specified directory
            $largeImage->save($playerImagePath."".$filename);

            // Define the path where the file will be stored
            //$path = $file->storeAs('uploads', $customFileName, 'public');
            //$baseFileName = basename($imagePath);
                      

            // Resize and convert the image to JPG
            $thumbImage = Image::make($uploadedFile->getPathname())
            ->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio(); // Maintain aspect ratio
                $constraint->upsize();     // Prevent upscaling
            })
            ->encode('jpg', 80); // Encode to JPG with 80% quality

            // Optionally crop the image to exactly 80x80
            $thumbImage->crop(80, 80);

            // Save the image to the specified path
            $thumbImage->save($thumbImagePath.$filename);
        }

        $status = $request->input('status', 'publish');

        // Current authenticated user ID
        $userId = Auth::id();

        // $imagePath = Storage::url($imagePath);

        $formData = [
            'player_name' => $request->player_name,
            'image' => $filename,
            'image_thumb' => $filename,
            
            'profile_type' => $request->profile_type,
            'type' => $request->type,
            'style' => $request->style,
            'dob' => $request->dob,

            'category_id' => $request->category,
            'nickname' => $request->nick_name,
            'last_played_league' => $request->last_played_league,
            'address' => $request->address,

            'city' => $request->city,
            'email' => $request->email,

            'status' => $status,
            'created_by' => $userId,
        ];

        \Log::info($formData);  

        try{

            $result = Player::create($formData);            

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

    public function ssssstore(Request $request)
    {

      

        try {

            // Validate the image
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:6000',
            ]);


            //\Log::info('Request Object:', ['data' => json_encode($request)]);

            $status = $request->input('status', 'publish');

            // Current authenticated user ID
            $userId = Auth::id();

            \Log::info(json_encode($request->file('image')));

            $image_path = "";

            // Store the uploaded image
            if ($request->file('image')) {

                $image_path = $request->file('image')->store('images', 'public');

                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'image_path' => $image_path,
                ], 200);               
            }

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'image_path' => $image_path,
            ], 200);

            $formData = [
                'player_name' => $request->player_name,
                'image' => $request->image,
                
                'profile_type' => $request->profile_type,
                'type' => $request->type,
                'style' => $request->style,
                'dob' => $request->dob,

                'category_id' => $request->category,
                'nickname' => $request->nick_name,
                'last_played_league' => $request->last_played_league,
                'address' => $request->address,

                'city' => $request->city,
                'email' => $request->email,

                'status' => $status,
                'created_by' => $userId,
            ];

            \Log::info(print_r($formData,true));

            $result = Player::create($formData);

            \Log::info(print_r($formData,true));

            return response()->json([
                'success' => true,
                'message' => 'Player added successfully.',
                'category' => array(),
            ]);
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
}
