<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{

    /**
     *
     *
     * @param  int $payer
     * @param  int $payee
     * @param  float $value
     * @param  int $status
     * @return Transaction
     */
    public function createTransection(int $payer,int $payee,float $value,int $status): Transaction
    {
        return Transaction::create([
            'payer' => $payer,
            'value' => $value,
            'payee' => $payee,
            'status' => $status
        ]);
    }
}
