<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Support\Facades\Validator;

use App\Services\ImageUploadService;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\PlayerRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class PlayerApiController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }    

    public function index(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('query', '');

        // Start the query builder for the Player model
        $itemQuery = Player::with(['playerStyle','playerType','playerProfile']); // Eager load the Player relationship

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
        
        $itemQuery->orderBy('player_name', 'asc');

        $list_per_page = intval(setting('list_per_page', 10));

        // Paginate the results
        $items = $itemQuery->paginate($list_per_page);

       $items->map(function($transaction){            
            $transaction->player_nickname = $transaction->player_nickname;
            $transaction->age = $transaction->age;
            $transaction->player_profile_name = $transaction->player_profile_name;
            $transaction->player_type_name = $transaction->player_type_name;
            $transaction->player_style_name = $transaction->player_style_name;
            $transaction->category_name = $transaction->category_name;
            return $transaction;
        });

        // Return the columns and items data in JSON format
        return response()->json($this->getActionPermissionsAndColumns($items));
    }

    public function store(PlayerRequest $request){

        $request->validated();

        $formData = $request->all();

        $dob = $request->input('dob', '');

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');               
            $filename = $this->imageService->uploadImageWithThumbnail($uploadedFile,'players');

            $formData['image'] = $filename;
            $formData['image_thumb'] = $filename;
        }

        if($dob){
            $formData['dob'] = $this->get_formated_date($dob);
        }
        
        $formData['status'] = $request->input('status', 'publish');
        $formData['created_by'] = Auth::id();

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
                'data' => array(),
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => __('Player not added.'),
                'errors' => [
                    'error' => [$e->getMessage()]
                ]
            ], 409);
        }        
    }

    public function view(Request $request, $id){

        $res = $this->get_response();

        // Define column names (localized)
        $columns = [];
        $columns['uniq_id'] = __('Unique Id');
        $columns['player_name'] = __('Player Name');
        $columns['nickname'] = __('Nick Name');
        $columns['category_name'] = __('Category');
        $columns['age'] = __('Age');
        $columns['type'] = __('Type');
        $columns['style'] = __('Style');
        $columns['last_played_league'] = __('Last Played League');
        $columns['address'] = __('Address');
        $columns['city'] = __('City');
        $columns['email'] = __('Email');
        $columns['formated_date'] = __('Creation Date');        

        try {
            $item = Player::select('image_thumb','uniq_id', 'player_name', 'nickname', 'mobile', 'email', 'category_id', 'dob', 'type', 'profile_type', 'style', 'last_played_league', 'address', 'city', 'created_at')
                    ->find($id);

            $item->category_name = $item->category?->category_name;
            $item->style = $item->style_label;
            $item->type = $item->type_label;
            $item->age = $item->age;
            $item->formated_date = $item->created_at->format('d-m-Y');

            if ($item) {
                return response()->json([
                    'success' => true,
                    'message' => 'Player successfully found.',
                    'data' => $item,
                    'rows' => $columns,
                ], 200);
            } else {
                $res['errors'] = ['player1' => [__('Player not found.')]];
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

    public function edit(Request $request, $id)
    {
        $res = $this->get_response();

        try {
            $item = Player::select('uniq_id', 'player_name', 'nickname', 'mobile', 'email', 'category_id', 'dob', 'type', 'profile_type', 'style', 'last_played_league', 'address', 'city')
                    ->find($id);

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

    public function update(PlayerRequest $request, $id)
    {
        $res = $this->get_response();

        $request->validated();

        $item = Player::findOrFail($id);

        try {

            $formData = $request->all();  
            
            $dob = $request->input('dob', '');

            if ($request->hasFile('image')) {

                $image = $item->image;

                $image_thumb = $item->image_thumb;
                
                $uploadedFile = $request->file('image');

                $filename = $this->imageService->uploadImageWithThumbnail($uploadedFile,'players');              

                $formData['image'] = $filename;

                $formData['image_thumb'] = $filename;               
               
                if($image){
                    $this->imageService->deleteMainFile($image,'players');
                }

                if($image_thumb){
                    $this->imageService->deleteThumbFile($image_thumb,'players');
                }
            }else{
                unset($formData['image']);
                unset($formData['image_thumb']);
            }

            if ($dob) {
                $formData['dob'] = $this->get_formated_date($dob);           
            }           
            
            $formData['status'] = $request->input('status', 'publish');;
            $formData['updated_by'] = Auth::id(); // Current authenticated user ID

            // Update the record
            $item->update($formData);

            return response()->json([
                'success' => true,
                'message' => 'Player updated successfully.',
                'data' => $item,
            ]);

            $res['success'] = true;
            $res['message'] = __('Player updated successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
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

        try {
            $item = Player::findOrFail($id); // Attempt to find the category by ID
            $deleted = $item->delete(); // Delete the category if found

            if($deleted){
                $item = Player::select('image')->find($id);

                if ($item) {
                    $image = $item->image; // Access the 'image' value            
                    $this->imageService->deleteSavedFile($image, 'players');
                }
            }           
            
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

    private function get_columns()
    {
        // Define column names (localized)
        $columns = [];
        $columns['sr'] = __('Sr.');
        $columns['uniq_id'] = __('Unique Id');
        $columns['image'] = __('Profile');
        $columns['player_nickname'] = __('Name');
        $columns['player_profile_name'] = __('Profile Type');
        $columns['player_type_name'] = __('Type');
        $columns['player_style_name'] = __('Style');
        $columns['age'] = __('Age');
        $columns['category_name'] = __('Category');        
        $columns['actions'] = __('Actions');

        return $columns;
    }

    private function getActionPermissionsAndColumns($items){
        $columns = $this->get_columns();
        $user = auth()->user(); // Get the logged-in user

        // Get permissions for the actions
        $actions = [];        
        $actions['edit'] = false;
        $actions['delete'] = false;
        $actions['view'] = false;

        $actions['edit'] = $user->can('player-edit');
        $actions['delete'] = $user->can('player-delete');
        $actions['view'] = $user->can('player-view');

        // Exclude the actions column if no actions are allowed
        if (!$actions['edit'] && !$actions['delete'] && !$actions['view']) {
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

    private function get_formated_date($dateInput = ''){
        if($dateInput != ""){
            // Replace the first '-' with a character that splits day, month, and year
            $formattedDate = Str::replaceFirst('-', '', $dateInput); // e.g., '251124' becomes '25-11-2024'
            $formattedDate = Str::replaceFirst('-', '', $formattedDate); // transforms to '2024-11-25'  
            return $formattedDate;
        }
        return $dateInput;
    }
}
