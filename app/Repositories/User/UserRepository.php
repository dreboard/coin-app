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
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

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
     * @param string $status
     * @return bool
     */
    public function editUserAccountStatus(int $id, string $status): bool
    {
        $user = User::find($id);
        $user->account_status = $status;
        $user->save();
        return true;
    }




}
