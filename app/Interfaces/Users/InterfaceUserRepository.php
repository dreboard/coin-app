<?php

namespace App\Interfaces\Users;

interface InterfaceUserRepository
{
    public function getAllUsers();

    public function getUserById(int $id);
}
