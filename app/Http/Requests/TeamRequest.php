<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Team;

class TeamRequest extends FormRequest
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
        $rules = [            
            'league_id' => 'required|integer|exists:league,id',
            'owner_name' => 'required|string',
            'owner_email' => 'required|email|string|max:100|unique:users,email',
            'owner_phone' => 'required|string',
            'owner_password' => 'required|string|max:100',
        ];

        $update_id = $this->input('update_id', 0);
        if($update_id > 0){
            $item = Team::findOrFail($update_id);
            $owner_id = $item->owner_id;
        }

        if ($this->isMethod('post')) {
            // Rules for creating a category
            if($update_id > 0){
                $rules['team_name'] = 'required|string|max:100|unique:teams,team_name,' . $update_id;
                $rules['owner_email'] = 'required|email|string|max:100|unique:users,email,' . $owner_id;
                $rules['owner_password'] = 'nullable|string|max:100';
            }else{
                $rules['team_name'] = 'required|string|max:100|unique:teams,team_name';
                $rules['team_logo'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            }            
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Rules for updating a category
            $rules['team_name'] = 'required|string|max:100|unique:teams,team_name,' . $this->route('category');            
            $rules['owner_email'] = 'required|email|string|max:100|unique:users,email,' . $owner_id;
            $rules['owner_password'] = 'nullable|string|max:100';
        }

        return $rules;

    }

     /**
     * Prepare the data for validation by setting default values.
     */
    protected function prepareForValidation()
    {
        // Set default value for 'status' if it's not provided
        $this->merge([
            'status' => $this->input('status', 'publish'), // Default to 'publish'
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
            'team_logo' => 'Team Profile image is required.'
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
