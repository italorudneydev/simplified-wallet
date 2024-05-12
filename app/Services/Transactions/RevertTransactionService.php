<?php

namespace App\Services\Transactions;

use App\Enums\TransactionStatus;
use App\Enums\WalletOperation;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Services\AuthorizeService;
use App\Services\UserService;
use App\Services\WalletService;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RevertTransactionService
{

    public function __construct(
        private readonly AuthorizeService      $authorizeService,
        private readonly TransactionRepository $transactionRepository,
        private readonly WalletService         $walletService
    )
    {
    }


    /**
     * @throws Exception
     */
    public function revertTransaction($transactionId): bool
    {
        DB::beginTransaction();
        try {
            $transaction = $this->transactionRepository->findById($transactionId);

            if (!$transaction) {
                throw new Exception("Transação não encontrada.");
            }

            if (!$this->canBeReversed($transaction)) {
                throw new Exception("Esta transação não pode ser revertida.");
            }

            $statusTransaction = $this->authorizeService->authorizeOperation();

            if (!$statusTransaction) {
                throw new Exception('Transaction Unauthorized', Response::HTTP_UNAUTHORIZED);
            }

            $this->transactionRepository->updateStatusTransaction($transactionId, TransactionStatus::CANCELLED->id());

            $this->registerReversal($transaction);

            DB::commit();

            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage(), 422);
        }
    }

    private function canBeReversed(Transaction $transaction): bool
    {
        return $transaction->status !== TransactionStatus::CANCELLED->id();
    }

    /**
     * @throws Exception
     */
    private function registerReversal(Transaction $originalTransaction): Transaction
    {
        $this->walletService->updateWallet($originalTransaction->payee, $originalTransaction->value, WalletOperation::ADDITION);
        $this->walletService->updateWallet($originalTransaction->payer, $originalTransaction->value, WalletOperation::SUBTRACTION);

        return $this->transactionRepository->createTransaction($originalTransaction->payee, $originalTransaction->payer, $originalTransaction->value, TransactionStatus::REVERSED->id());
    }
}
