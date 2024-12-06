<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;

class TeamPlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {       
        $unique = Rule::unique('sold_players')->where(function ($query) {
            return $query->where('player_id', $this->player_id)->where('team_id', $this->team_id);
        });
       
        $rules = [
            'player_id' => ['required','integer',$unique],
            'team_id' => ['required','integer']
        ];        

        return $rules;

    }

     /**
     * Prepare the data for validation by setting default values.
     */
    protected function prepareForValidation()
    {
        $player = Player::with('category')->find($this->player_id);

        $team = Team::find($this->team_id);

        if (!$player || !$team) {
            $this->merge([
                'invalid_player_or_team' => true,
            ]);
            return;
        }

        $category_id = $player->category->id ?? null;
        $league_id = $team->league_id;
        $baseprice = $player->category->base_price ?? 0;

        // Calculate the adjusted base price
        $baseprice = $baseprice > 0 ? $baseprice + ($baseprice / 2) : 0;

        // Merge calculated fields into the request data
        $this->merge([
            'category_id' => $category_id,
            'league_id' => $league_id,
            'sold_price' => $baseprice,
        ]);       
    }

    /**
     * Get custom error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'team_id.unique' => 'The selected player is exists.',
        ];
    }

    /**
     * Override failedValidation to return a JSON response.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        // Create a custom response format
        $response = response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
