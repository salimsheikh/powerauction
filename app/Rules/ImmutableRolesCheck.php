<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class ImmutableRolesCheck implements Rule
{
    protected $immutableRoles;
    protected $missingRoles = [];

    public function __construct(array $immutableRoles)
    {
        $this->immutableRoles = $immutableRoles;
    }

    /**
     * Validate the selected roles.
     */
    public function passes($attribute, $value)
    {
        // Ensure $value is an array
        if (!is_array($value)) {
            return false;
        }

        // Check for missing immutable roles
        $this->missingRoles = array_diff($this->immutableRoles, $value);

        return empty($this->missingRoles);
    }

    /**
     * Error message.
     */
    public function message()
    {
        $missingList = implode(', ', $this->missingRoles);
        $missingList = Str::title(str_replace('-', ' ', $missingList));
        return "The following roles cannot be disabled: {$missingList}.";
    }
}

