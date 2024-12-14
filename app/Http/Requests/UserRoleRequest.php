<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Role;
use App\Rules\ImmutablePermissionsCheck;

class UserRoleRequest extends FormRequest
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
        // Base validation rules
        $rules = [            
            'name' => 'required|string|max:100|unique:roles,name',
            'permission' => ['required','array','min:1'],
        ];

        $name = $this->input('name', '');
        if (in_array($name, ['admin', 'super-admin', 'Admin', 'Administrator'])) {
            $immutablePermissions = config('permissions.immutable_permissions');
            $rules['permission'][] = new ImmutablePermissionsCheck($immutablePermissions);
        }

        $update_id = $this->input('update_id', 0);

        //\Log::info($update_id);

        if ($this->isMethod('post')) {
            // Rules for creating a role
            if($update_id > 0){
                $rules['name'] = 'required|string|max:100|unique:roles,name,' . $update_id;
            }            
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Rules for updating a role
            $rules['name'] = 'required|string|max:100|unique:roles,id,' . $this->route('roles');
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
            'name.max' => 'User role name must not exceed 100 characters.',
            'permission.immutable_permissions_check' => 'You cannot disable immutable permissions required for your role.',
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

    /**
     * Custom method to check if all immutable permissions are present.
     */
    private function areImmutablePermissionsPresent(array $selectedPermissions, array $immutablePermissions): bool
    {
        foreach ($immutablePermissions as $permission) {
            if (!in_array($permission, $selectedPermissions)) {
                return false;
            }
        }
        return true;
    }
}
