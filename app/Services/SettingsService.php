<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Get the setting value by key with caching.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", now()->addHours(12), function () use ($key, $default) {
            $value = \App\Models\Setting::where('option_name', $key)->value('option_value');
            return $value !== null ? $value : $default;
        });
    }

    /**
     * Update or create a setting and update.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function updateSetting(string $key, $value)
    {
        // Update or create the setting in the database
        $setting = \App\Models\Setting::updateOrCreate(
            ['option_name' => $key],
            ['option_value' => $value]
        );
        return false;
    }

    
}
