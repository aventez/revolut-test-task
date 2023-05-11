<?php

namespace App\Enum;

enum OrderStatus: string
{
    case NEW = 'NEW';
    case PROCESSING = 'PROCESSING';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';
    case FAILED = 'FAILED';
}