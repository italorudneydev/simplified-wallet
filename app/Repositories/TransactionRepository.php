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
    public function createTransaction(int $payer, int $payee, float $value, int $status): Transaction
    {
        return Transaction::create([
            'payer' => $payer,
            'value' => $value,
            'payee' => $payee,
            'status' => $status
        ]);
    }

    public function findById($id): ?Transaction
    {
        return Transaction::where('id', $id)->first();
    }

    public function updateStatusTransaction(int $id, int $status): Transaction
    {
        $transaction = Transaction::find($id);
        $transaction->update([
            'status' => $status
        ]);
        return $transaction->fresh();
    }
}
