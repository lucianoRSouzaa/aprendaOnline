<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\LoginSuccessful;
use App\Models\Login;

class LoginSuccessfulListener
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
    public function handle(object $event): void
    {
        $login = new Login([
            'user_id' => $event->user_id,
            'login_time' => $event->login_time,
        ]);
    
        $login->save();
    }
}
