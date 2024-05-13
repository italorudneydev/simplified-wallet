<?php

namespace App\Http\Controllers;

use App\Exceptions\ConflictException;
use App\Exceptions\ServiceUnavailableException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\TransactionRequest;
use App\Services\Transactions\NewTransactionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class NewTransactionController extends Controller
{

    public function __construct(
        private readonly NewTransactionService $transactionService,
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
        }
        catch (ConflictException|ServiceUnavailableException|UnauthorizedException|Exception $e){
            Log::error('Error on ' . self::class, [
                'exception' => $e,
                'code' => 'new_transaction_error',
            ]);

            return new JsonResponse([
                'error' => __('message.erro'),
                'details' => $e->getMessage(),
            ], $e->getStatusCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
