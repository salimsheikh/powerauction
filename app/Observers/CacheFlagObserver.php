<?php

namespace App\Observers;

class CacheFlagObserver
{
    public function saved($model)
    {
        $this->updateCacheFlag();
    }

    public function deleted($model)
    {
        $this->updateCacheFlag();
    }

    private function updateCacheFlag()
    {
        $cache_flag = setting('cache_flag',false);
        if (!$cache_flag) {
            $key = 'cache_flag';
            updateSetting($key,true);
            \Illuminate\Support\Facades\Cache::forget("setting_{$key}");
        }
    }
}
