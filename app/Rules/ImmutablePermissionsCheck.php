<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ImmutablePermissionsCheck implements Rule
{
    protected $immutablePermissions;
    protected $missingPermissions = [];

    public function __construct(array $immutablePermissions)
    {
        $this->immutablePermissions = $immutablePermissions;
    }

    /**
     * Validate the selected permissions.
     */
    public function passes($attribute, $value)
    {
        // Ensure $value is an array
        if (!is_array($value)) {
            return false;
        }

        // Check for missing immutable permissions
        $this->missingPermissions = array_diff($this->immutablePermissions, $value);

        return empty($this->missingPermissions);
    }

    /**
     * Error message.
     */
    public function message()
    {
        $missingList = implode(', ', $this->missingPermissions);
        return "The following permissions cannot be disabled: {$missingList}.";
    }
}

