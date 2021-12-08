<?php

namespace App\Listeners\Banned;

use App\Events\BannedUser;
use App\Mail\Users\NotifyBannedUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBannedUserNotification
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
     * @param  \App\Events\BannedUser  $event
     * @return void
     */
    public function handle(BannedUser $event)
    {
        $user = $event->user;
        Mail::to($event->user)->send(new NotifyBannedUser($event->length, $event->user));


        /*Mail::send('email.user.banned', ['user' => $user], function ($message) use ($user, $event) {
            $message->from('admin@admin.com', 'John Doe');
            $message->subject('Your account has been suspended for '. $event->length .' days');
            $message->to($user->email);
        });*/
    }
}
