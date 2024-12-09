<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class ClearCacheListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CacheClearEvent $event): void
    {
        if ($event->cacheKey) {
            Cache::forget($event->cacheKey);
        } else {
            Cache::flush();
        }
    }
}
