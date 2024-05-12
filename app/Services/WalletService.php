<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Enums\WalletOperation;
use App\Models\Wallet;
use App\Repositories\WalletRepository;
use Exception;

class WalletService
{

    public function __construct(
        private readonly WalletRepository      $walletRepository,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function updateWallet($userId, $value, $operation)
    {
        $wallet = $this->walletRepository->getByUserId($userId);

        $this->updateBalance($wallet, $value, $operation);
    }

    public function updateBalance(Wallet $wallet, float $amount, WalletOperation $operation): Wallet
    {
        $balance = $this->calculateNewBalance($wallet, $amount, $operation);
        return $this->walletRepository->updateValueWallet($wallet->id, $balance);
    }

    /**
     * @throws Exception
     */
    private function calculateNewBalance(Wallet $wallet, float $amount, WalletOperation $operation): float
    {
        switch ($operation) {
            case WalletOperation::ADDITION:
                return $wallet->value += $amount;
            case WalletOperation::SUBTRACTION:
                $this->ensureSufficientBalance($wallet, $amount);
                return $wallet->value -= $amount;
            default:
                throw new Exception("Operação inválida.");
        }
    }

    private function ensureSufficientBalance(Wallet $wallet, float $amount): void
    {
        if ($wallet->value < $amount) {
            throw new Exception("Saldo insuficiente para a operação de subtração.");
        }
    }


}
