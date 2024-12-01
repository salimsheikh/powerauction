<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class PlayerRequest extends FormRequest
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
            'player_name' => 'required|string|max:100',            
            'profile_type' => 'required|string',
            'type' => 'required|string',
            'style' => 'required|string|max:100',
            'dob' => 'required|date',
            'category_id' => 'required|integer|exists:categories,id',
            'nickname' => 'required|string|max:100',
            'last_played_league' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',            
            'status' => 'required|string|in:publish,draft',  // Validation rule for 'status'
        ];

        $update_id = $this->input('update_id', 0);

        if ($this->isMethod('post')) {
            // Rules for creating a player
            if($update_id > 0){
                $rules['email'] = 'required|email|max:100|unique:players,email,' . $update_id;
            }else{
                $rules['email'] = 'required|email|max:100|unique:players,email';
                $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            }            
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Rules for updating a player
            $rules['email'] = 'required|email|max:100|unique:players,email,' . $this->route('player');
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
        return [];
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
