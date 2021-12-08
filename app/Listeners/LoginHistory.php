<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginHistory
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Save user login to the login_history table
     *
     * @param Login $event
     * @return Login|object
     */
    public function handle(Login $event)
    {
        DB::table('login_history')->insert([
            'user_id' => $event->user->id,
            'created_at' => now()
        ]);
        return $event;
    }
}
