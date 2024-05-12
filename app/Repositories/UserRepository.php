<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    public function findShopKeeperById($id)
    {
        return User::where('id', $id)
            ->IsShopKeeper()
            ->get();
    }
}
