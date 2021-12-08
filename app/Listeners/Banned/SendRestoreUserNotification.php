<?php

namespace App\Listeners\Banned;

use App\Events\UnbanUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\Users\RestoreBannedUser;

class SendRestoreUserNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UnbanUser  $event
     * @return void
     */
    public function handle(UnbanUser $event)
    {
        $user = $event->user;
        Mail::to($event->user)->send(new RestoreBannedUser($event->user));


/*
        Mail::send('email.user.banned', ['user' => $user], function ($message) use ($user, $event) {
            $message->from('admin@admin.com', 'John Doe');
            $message->subject('Your account has been restored');
            $message->to($user->email);
        });*/
    }
}
