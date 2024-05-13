<?php

namespace App\Jobs;

use App\Services\NotificationService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $receiverService;

    public function __construct($message, $receiverService)
    {
        $this->message = $message;
        $this->receiverService = $receiverService;
    }

    /**
     * @throws GuzzleException
     */
    public function handle(NotificationService $notificationService): void
    {
     $notificationService->sendNotification($this->message, $this->receiverService);
    }

    public function failed(Exception $exception): void
    {
        dispatch((new NotifyUser($this->message, $this->receiverService))->onQueue('notifications'));
    }
}
