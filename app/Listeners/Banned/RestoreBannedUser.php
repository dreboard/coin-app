<?php

namespace App\Listeners\Banned;

use App\Events\UnbanUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class RestoreBannedUser
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
        try{
            $event->user->unban();
        } catch (Exception $e) {
            Log::error('caught in '. __CLASS__.' '.$e->getMessage());
            throw $e;
        }
    }
}
