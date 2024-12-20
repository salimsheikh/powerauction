<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use App\Models\Team;

class LogUserLogin
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
    public function handle(Login $event): void
    {
        // Get the authenticated user
        $user = $event->user;

        $user_id = $user->id;

        $team_id = Team::where('owner_id', $user_id)->value('id');

        $owner_team_id = $team_id > 0 ? $team_id : null;
        
        Session::put('owner_team_id', $owner_team_id); 
    }
}
