<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;


class UserLoggedIn
{
    use Dispatchable, SerializesModels;

    public $user;
    public $request;

    public function __construct($user, Request $request)
    {
        $this->user = $user;
        $this->request = $request;
    }
}
