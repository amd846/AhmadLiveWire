<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use Illuminate\Support\Facades\Log;


class LogUserLogin
{
    public function handle(UserLoggedIn $event)
    {
        $user = $event->user;
        $request = $event->request;

        // Log the user login details
        $requestTime = $request->server('REQUEST_TIME');

        Log::info("User Logged In: {$user->name} - IP: {$request->ip()} - Time: " . date('Y-m-d H-i-s:v', $requestTime));
        $user->update([
            'userLogIn_at' => now(),
        ]);        
     }
}
