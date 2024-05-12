<?php

namespace App\Http\Controllers;

use App\Services\Transactions\RevertTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RevertTransactionController extends Controller
{
    protected $transactionService;

    public function __construct(RevertTransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function __invoke(Request $request, $transactionId): JsonResponse
    {
        try {
            $result = $this->transactionService->revertTransaction($transactionId);

            return response()->json([
                'message' => 'Transaction successfully reversed',
                'transaction' => $result
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?? 400);
        }
    }
}
