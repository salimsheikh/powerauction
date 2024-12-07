<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

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
            $value = Setting::where('option_name', $key)->value('option_value');
            return $value !== null ? $value : $default;
        });
    }
}
