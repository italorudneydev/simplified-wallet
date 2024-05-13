<?php

namespace App\Enums;

enum NotificationStatus: int
{
    case SENT = 1;
    case PENDING = 2;
}
