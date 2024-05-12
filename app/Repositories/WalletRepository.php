<?php

namespace App\Repositories;

use App\Models\Wallet;

class WalletRepository
{
    /**
     * Get a Wallet by user ID.
     *
     * @param  int $userId
     * @return Wallet|null
     */
    public function getByUserId(int $userId): ?Wallet
    {
        return Wallet::where('user_id', $userId)->first();
    }

    public function updateValueWallet($walletId, float $amount)
    {
        $wallet = Wallet::find($walletId);

        if ($wallet) {
            $wallet->update(['value' => $amount]);
            return $wallet->fresh();
        }

        return null;
    }

}
