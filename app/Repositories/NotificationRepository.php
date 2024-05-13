<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository
{

    public function createNotification(int $transactionId, int $status): Notification
    {
        return Notification::create([
            'transaction_id' => $transactionId,
            'status' => $status
        ]);
    }

}
