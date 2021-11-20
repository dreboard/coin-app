<?php

namespace App\Repositories\User;

use App\Interfaces\Users\InterfaceUserRepository;
use App\Models\User;

/**
 *
 */
class UserRepository implements InterfaceUserRepository
{


    /**
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        return User::all();
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




}
