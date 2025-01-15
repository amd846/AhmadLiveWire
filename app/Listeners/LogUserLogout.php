<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserLoggedOut;
use Illuminate\Support\Facades\Log;

class LogUserLogout
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
    public function handle(UserLoggedOut $event)
    {
        $user = $event->user;
        $request = $event->request;

        // Log the user login details
        //Log::info("User Logged Out: {$user->name} - IP: {$request->ip()} - Time: {$request->server('REQUEST_TIME')}");
        $requestTime = $request->server('REQUEST_TIME');

Log::info("User Logged Out: {$user->name} - IP: {$request->ip()} - Time: " . date('Y-m-d H-i-s:v', $requestTime));
$user->update([
    'userLogOut_at' => now(),
]); 
    }
}
