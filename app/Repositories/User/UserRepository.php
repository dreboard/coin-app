<?php

namespace App\Repositories\User;

use App\Interfaces\Users\InterfaceUserRepository;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 *
 */
class UserRepository implements InterfaceUserRepository
{


    /**
     * @return
     */
    public function getAllUsers()
    {
        return User::withoutBanned()->get();
    }

    /**
     * @return
     */
    public function getAllBannedUsers()
    {
        return User::onlyBanned()->get();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getUserById(int $id)
    {
        return User::find($id);
    }


    /**
     * @param int $id
     * @param int $status
     * @return bool
     */
    public function editUserAccountStatus(int $id, int $status): bool
    {
        $user = User::find($id);
        $user->account_status = $status;
        $user->save();
        return true;
    }

    /**
     * Ban a user for a specified amount of days
     *
     * @param int $until
     * @param $user_id
     */
    public function ban(int $user_id, int $until = 7)
    {
        // $until = 7 days
        // $until = 14 days
        // ban_permanently = 0
        $user = User::find($user_id);
        $user->banned_till = Carbon::now()->addDays($until);
        $user->save();
    }


}
