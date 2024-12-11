<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:191',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'nullable|string|min:8',
            'roles' => 'required',
        ];

        $update_id = $this->input('update_id', 0);
       

        if ($this->isMethod('post')) {
            // Rules for creating a role
            if($update_id > 0){
                $rules['email'] = 'required|string|email|max:150|unique:users,email,' . $update_id;
                $rules['password'] = 'nullable|string|max:100';
            }
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Rules for updating a role
            $rules['email'] = 'required|string|email|unique:users,id,' . $this->route('roles');
            $rules['password'] = 'nullable|string|max:100';
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
            'guard_name' => $this->input('guard_name', 'web'), // Default to 'publish'
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
            'name' => 'User role name is required.',
            
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
