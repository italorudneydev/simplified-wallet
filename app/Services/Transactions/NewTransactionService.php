<?php

namespace App\Services\Transactions;

use App\Enums\TransactionStatus;
use App\Enums\WalletOperation;
use App\Repositories\TransactionRepository;
use App\Services\AuthorizeService;
use App\Services\UserService;
use App\Services\WalletService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class NewTransactionService
{

    public function __construct(
        private readonly AuthorizeService      $authorizeService,
        private readonly TransactionRepository $transactionRepository,
        private readonly WalletService         $walletService,
        private readonly UserService           $userService
    )
    {
    }

    /**
     * @throws Exception
     */
    public function newTransaction(array $data): bool
    {

        $newTransaction = null;

        DB::beginTransaction();
        try {


            $payerId = Arr::get($data, 'payer');
            $payeeId = Arr::get($data, 'payee');
            $value = Arr::get($data, 'value');

            if ($this->userService->isShopKeeper($payerId)) {
                throw new Exception("Merchants are not allowed to make payments", Response::HTTP_FORBIDDEN);
            }

            $statusTransaction = $this->authorizeService->authorizeOperation();

            if (!$statusTransaction) {
                throw new Exception('Transaction Unauthorized', Response::HTTP_UNAUTHORIZED);
            }

            $this->walletService->updateWallet($payerId, $value, WalletOperation::SUBTRACTION);
            $this->walletService->updateWallet($payeeId, $value, WalletOperation::ADDITION);

            $transaction = $this->transactionRepository->createTransaction($payerId, $payeeId, $value, TransactionStatus::APPROVED->id());

            $this->notifyPayee($payeeId, $transaction->id);

            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage(), $ex->getCode() ?? 422);
        }
    }

    public function notifyPayee(int $userId = 1, int $transactionId = 1)
    {
        return true;
    }

    public function revertTransaction(array $data): bool
    {

    }
}
