<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class LeagueRequest extends FormRequest
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
            'league_name' => 'required|string|max:100|unique:league,league_name',      
            'description' => 'required|string',
            'status' => 'required|string|in:0',  // Validation rule for 'status'
        ];

        $update_id = $this->input('update_id', 0);

        if ($this->isMethod('post')) {
            // Rules for creating a league
            if($update_id > 0){
                $rules['league_name'] = 'required|string|max:100|unique:league,league_name,' . $update_id;
            }else{
                $rules['league_name'] = 'required|string|max:100|unique:league,league_name';
            }            
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Rules for updating a league
            $rules['league_name'] = 'required|string|max:100|unique:league,league_name,' . $this->route('league');
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
            'status' => $this->input('status', '0'), // Default to 'publish'
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
