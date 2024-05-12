<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'CANCELLED';

    public function id(): int
    {
        return match($this) {
            self::PENDING => 1,
            self::APPROVED => 2,
            self::REJECTED => 3,
            self::CANCELLED => 4,
        };
    }

    public function title(): string
    {
        return match($this) {
            self::PENDING => 'Pending Approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::CANCELLED => 'Cancelled',
        };
    }
}
