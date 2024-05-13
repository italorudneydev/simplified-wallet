<?php

namespace App\Services;

use App\Enums\NotificationStatus;
use App\Repositories\NotificationRepository;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class NotificationService
{

    public function __construct(
        private CircuitBreaker $circuitBreaker,
        private NotificationRepository $notificationRepository,
    )
    {

    }

    /**
     * @throws Exception|GuzzleException
     */
    public function sendNotification(int $userId, int $transactionId)
    {
        $status = NotificationStatus::PENDING;
        try {

            $response = $this->circuitBreaker->call('GET', config('app.external_apis.notification_api'));
            $data = $response->getBody()->getContents();

        } catch (Exception $e) {
            Log::error("Erro ao conectar com o serviço: " . $e->getMessage());
        }

        $this->saveNotification($transactionId, $status);
    }

    private function saveNotification(int $transactionId, int $status): void
    {
        $this->notificationRepository->createNotification($transactionId, $status);
    }

}
