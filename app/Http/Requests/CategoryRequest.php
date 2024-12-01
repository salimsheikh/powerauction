<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CategoryRequest extends FormRequest
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
            'base_price' => 'required|numeric',
            'description' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:10',
            'status' => 'required|string|in:publish,draft',  // Validation rule for 'status'
        ];

        $update_id = $this->input('update_id', 0);

        if ($this->isMethod('post')) {
            // Rules for creating a category
            if($update_id > 0){
                $rules['category_name'] = 'required|string|max:150|unique:categories,category_name,' . $update_id;
            }else{
                $rules['category_name'] = 'required|string|max:150|unique:categories,category_name';
            }            
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Rules for updating a category
            $rules['category_name'] = 'required|string|max:150|unique:categories,category_name,' . $this->route('category');
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
            'category_name.required' => 'Category name is required!',
            'category_name.unique' => 'This category name is already taken!',
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
