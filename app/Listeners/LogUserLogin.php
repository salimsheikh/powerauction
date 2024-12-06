<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
        \Log::info($team_id);

        // Log the user's team ID (assuming the user has a team_id field)
        \Log::info('User logged in:', [
            'user_id' => $user_id,
            'team_id' => $team_id, // Assuming team_id exists
            'session_id' => session()->getId(), // Log the session ID
        ]);
        
    }
}
