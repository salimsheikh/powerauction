<?php

namespace App\Http\Controllers\Backend\Api;
use App\Http\Controllers\Controller;
use App\Models\{User,Team,SoldPlayer};


use Illuminate\Support\Facades\{Validator,Hash,Auth};
use Illuminate\Auth\Events\Registered;

use App\Services\ImageUploadService;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Requests\TeamRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class TeamApiController extends Controller
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

        $page = $request->input('page', '');
        
        try {
            $items = Team::getTeams($query);
        } catch (\Throwable $th) {
            //throw $th;
            $items = [];
        }               

        // Return the columns and items data in JSON format
        return response()->json($this->getActionPermissionsAndColumns($items));
    }

    public function store(TeamRequest $request){        

        $request->validated();

        $userData = [
            'name' => $request->owner_name,
            'email' => $request->owner_email,
            'phone' => $request->owner_phone,
            'role' => 'team',
            'created_by' => Auth::id(),
            'password' => Hash::make($request->owner_password),
        ];

        $user = User::create($userData);     

        event(new Registered($user));

        $formData = $request->all();        

        if ($request->hasFile('team_logo')) {
            $uploadedFile = $request->file('team_logo');               
            $filename = $this->imageService->uploadImageWithThumbnail($uploadedFile,'teams');

            $formData['team_logo'] = $filename;
            $formData['team_logo_thumb'] = $filename;
        }

        $formData['virtual_point'] = 0;
        $formData['owner_id'] = $user->id;
        
        $formData['status'] = $request->input('status', 'publish');
        $formData['created_by'] = Auth::id();

    

        try{

            // Create a new record
            $result = Team::create($formData);

            // Get the inserted record's ID
            $insertedId = $result->id;

            // Generate a unique ID (for example, using a UUID)
            $uniqueId = "SPL/".$insertedId;

            // Update the record with the unique ID
            $result->update(['uniq_id' => $uniqueId]);

            return response()->json([
                'success' => true,
                'message' => 'Team added successfully.',
                'data' => array(),
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => __('Team not added.'),
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
        $columns['team_name'] = __('Team Name');
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
            $item = Team::select('image_thumb','uniq_id', 'team_name', 'nickname', 'mobile', 'email', 'category_id', 'dob', 'type', 'profile_type', 'style', 'last_played_league', 'address', 'city', 'created_at')
                    ->find($id);

            $item->category_name = $item->category?->category_name;
            $item->style = $item->style_label;
            $item->type = $item->type_label;
            $item->age = $item->age;
            $item->formated_date = $item->created_at->format('d-m-Y');

            if ($item) {
                return response()->json([
                    'success' => true,
                    'message' => 'Team successfully found.',
                    'data' => $item,
                    'rows' => $columns,
                ], 200);
            } else {
                $res['errors'] = ['team1' => [__('Team not found.')]];
                $res['message'] = __('An unexpected error occurred.');
                $res['statusCode'] = 404;
                return jsonResponse($res);
            }
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['team' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['team' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function edit(Request $request, $id)
    {
        $res = $this->get_response();

        try {
            $item = Team::select('teams.team_name','teams.league_id','users.name as owner_name', 'users.email as owner_email', 'users.phone as owner_phone')
                    ->join('users', 'teams.owner_id', '=', 'users.id')
                    ->find($id);

            if ($item) {
                return response()->json([
                    'success' => true,
                    'message' => 'Team successfully found.',
                    'data' => $item,
                ], 200);
            } else {
                $res['errors'] = ['team' => [__('Team not found.')]];
                $res['message'] = __('An unexpected error occurred.');
                $res['statusCode'] = 404;
                return jsonResponse($res);
            }
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['team' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['team' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function update(TeamRequest $request, $id)
    {
        $res = $this->get_response();

        $request->validated();

        $item = Team::findOrFail($id);

        $owner_id = $item->owner_id;       

        $item = Team::findOrFail($id);
        $owner = User::findOrFail($owner_id);

        $ownerData = [];

        $ownerData['phone'] = $request->owner_phone;
        $ownerData['name'] = $request->owner_name;

        if($request->owner_password != ""){
            $ownerData['password'] = Hash::make($request->owner_password);
        }

        $owner->update($ownerData);

        try {

            $formData = $request->all();             
           

            if ($request->hasFile('team_logo')) {

                $image = $item->team_logo;

                $image_thumb = $item->team_logo_thumb;
                
                $uploadedFile = $request->file('team_logo');

                $filename = $this->imageService->uploadImageWithThumbnail($uploadedFile,'teams');              

                $formData['team_logo'] = $filename;

                $formData['team_logo_thumb'] = $filename;               
               
                if($image){
                    $this->imageService->deleteMainFile($image,'teams');
                }

                if($image_thumb){
                    $this->imageService->deleteThumbFile($image_thumb,'teams');
                }
            }else{
                unset($formData['image']);
                unset($formData['image_thumb']);
            }                    
            
            $formData['status'] = $request->input('status', 'publish');;
            $formData['updated_by'] = Auth::id(); // Current authenticated user ID

            // Update the record
            $item->update($formData);

            return response()->json([
                'success' => true,
                'message' => 'Team updated successfully.',
                'data' => $item,
            ]);

            $res['success'] = true;
            $res['message'] = __('Team updated successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['team' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['team' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 500;
            return jsonResponse($res);
        }
    }

    public function destroy($id)
    {
        $res = $this->get_response();

        $item = Team::select('team_logo')->find($id);

        if ($item) {
            $image = $item->team_logo; // Access the 'image' value            
            $this->imageService->deleteSavedFile($image, 'teams');
        }

        try {
            $item = Team::findOrFail($id); // Attempt to find the category by ID
            $item->delete(); // Delete the category if found
            $res['success'] = true;
            $res['message'] = __('Team deleted successfully');
            $res['statusCode'] = 201;
            return jsonResponse($res);
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['Team not found' => [$e->getMessage()]];
            $res['message'] = __('An unexpected error occurred.');
            $res['statusCode'] = 404;
            return jsonResponse($res);
        } catch (Exception $e) {
            $res['errors'] = ['team_delete' => [$e->getMessage()]];
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
        $columns['team_logo_thumb'] = __('Logo');
        $columns['team_name'] = __('Team Name');
        $columns['owner_name'] = __('Owner Name');
        $columns['owner_email'] = __('Owner Email');
        $columns['owner_phone'] = __('Owner Phone');
        $columns['virtual_point'] = __('Virtual Point');
        $columns['remaining_points'] = __('Remaining Points');
        $columns['league_name'] = __('League');
        $columns['team_players'] = __('Players');
        $columns['actions'] = __('Actions');

        return $columns;
    }

    private function getActionPermissionsAndColumns($items){
        $columns = $this->get_columns();
        $user = auth()->user(); // Get the logged-in user

        // Get permissions for the actions
        $actions = [];        
        $actions['edit'] = $user->can('team-edit');
        $actions['delete'] = $user->can('team-delete');
        $actions['booster'] = $user->can('team-booster');
        $actions['team-view-player'] = $user->can('team-view-player');

        // Exclude the actions column if no actions are allowed
        if (!$actions['edit'] && !$actions['delete'] && !$actions['booster']) {
            unset($columns['actions']);
        }

        if (!$actions['team-view-player']) {
            unset($columns['team_players']);
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
