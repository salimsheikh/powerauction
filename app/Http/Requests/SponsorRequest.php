<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Sponsor;

class SponsorRequest extends FormRequest
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
            'sponsor_name' => 'required|string|max:100|unique:sponsors,sponsor_name',
            'sponsor_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',      
            'sponsor_description' => 'required|string|max:500',
            'sponsor_website_url' => 'required|string|max:200',
            'sponsor_type' => 'required|string|max:10'
        ];

        $update_id = $this->input('update_id', 0);

        if ($this->isMethod('post')) {
            // Rules for creating a sponsor
            if($update_id > 0){
                $rules['sponsor_name'] = 'required|string|max:100|unique:sponsors,sponsor_name,' . $update_id;
                unset($rules['sponsor_logo']);
            }            
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Rules for updating a sponsor
            $rules['sponsor_name'] = 'required|string|max:100|unique:sponsors,sponsor_name,' . $this->route('sponsor');
            unset($rules['sponsor_logo']);
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
