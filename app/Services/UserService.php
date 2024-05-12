<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }
    public function isShopKeeper(int $userId): bool
    {
        $user = $this->userRepository->findShopKeeperById($userId);
        return !$user->isEmpty();
    }
}
