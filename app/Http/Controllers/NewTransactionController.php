<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class NewTransactionController extends Controller
{

    public function __construct(
        private readonly TransactionService $transactionService,
    )
    {
    }

    public function __invoke(TransactionRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            return new JsonResponse(
                $this->transactionService->newTransaction($data),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            Log::error('Error on ' . self::class, [
                'exception' => $e,
                'code' => 'new_transaction_error',
            ]);

            return new JsonResponse([
                'error' => __('message.erro'),
                'details' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
