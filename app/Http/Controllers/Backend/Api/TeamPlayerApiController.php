<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TeamPlayerRequest;
use App\Models\SoldPlayer;
use App\Models\Player;
use App\Models\Team;
use App\Models\Category;
use Illuminate\Support\Facades\Session;

class TeamPlayerApiController extends Controller
{
    public function index(Request $request)
    {
        $formData = $request->all();        

        $master_id = $request->input('master_id', '');        

        // Get the search query from the request
        $query = $request->input('query', '');

        // Start the query builder for the Player model
        $itemQuery = Player::with(['category']); // Eager load the Player relationship

        $itemQuery->select([
            'players.*',
            'sp.id AS sold_player_id',
            'sp.team_id',
            'sp.sold_price',
        ]);

        $itemQuery->join('sold_players AS sp', 'players.id', '=', 'sp.player_id');

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

        $itemQuery->where('sp.team_id', $master_id);

        $itemQuery->where('players.status', 'publish');

        // Order by category_name in ascending order
        $itemQuery->orderBy('players.created_at', 'desc');

        // Paginate the results
        $items = $itemQuery->paginate(10);

        foreach ($items as $item) {
            // Modify attributes as needed
            $item->category_name = ''; // Ensure this value is actually required
            $item->age = '';
            $item->player_nickname = '';
            $item->profile_type_label = '';
            $item->type_label = '';
            $item->style_label = '';
        }

        $columns = $this->get_columns();

        // Return the columns and items data in JSON format
        return response()->json([
            'columns' => $columns,
            'items' => $items
        ]);
    }

    public function store(TeamPlayerRequest $request)
    {
        $request->validated();

        try {

            $formData = $request->all();        
                        
            // Create the new record
            $soldPlayer = SoldPlayer::create($formData);            

            return response()->json([
                'success' => true,
                'message' => __('Player added successfully.'),
                'data' => $soldPlayer,
                'team_player_ids' => $this->getSelectedPlayers($request->team_id),
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

    function getSelectedPlayers($team_id){
        return SoldPlayer::where('team_id', $team_id)->pluck('player_id')->toArray();
    }

    public function destroy($id)
    {
        $res = $this->get_response();
        
        try {
            $team_id = SoldPlayer::where('id', $id)->value('team_id');

            $item = SoldPlayer::findOrFail($id);

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => __('Player deleted successfully.'),
                'data' => '',
                'team_player_ids' => $this->getSelectedPlayers($team_id),
            ]);            
        } catch (ModelNotFoundException $e) {
            $res['errors'] = ['Team not found' => [$e->getMessage()]];
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
        $columns['profile_type_label'] = __('Profile Type');
        $columns['type_label'] = __('Type');
        $columns['style_label'] = __('Style');
        $columns['age'] = __('Age');
        $columns['category_name'] = __('Category');        
        $columns['team_player_actions'] = __('Actions');

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
