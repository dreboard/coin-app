<?php

namespace App\Listeners\Banned;

use App\Events\BannedUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnterBannedUser
{

    private const MAX_BANS = 3;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private function countUserBanEntries($id)
    {
        return DB::table('bans')->where(
            'bannable_id', '=', $id
        )->count();
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\BannedUser $event
     * @return void
     * @throws Exception
     */
    public function handle(BannedUser $event)
    {
        try{
            if($this->countUserBanEntries($event->user->id) > self::MAX_BANS || $event->length == 0){
                $ban = $event->user->ban([
                    'expired_at' => null,
                ]);
                $ban->isPermanent();
            } else {
                $ban = $event->user->ban([
                    'expired_at' => Carbon::now()->addDays($event->length),
                ]);
                $ban->isTemporary();
            }
        } catch (Exception $e) {
            Log::error('caught in '. __CLASS__.' '.$e->getMessage());
            throw $e;
        }

    }
}
