<?php

namespace App\Enum;

enum OrderStatus: string
{
    case taken = 'taken';
    case inCooking = 'inCooking';
    case pendingPayment = 'pendingPayment';
    case complete = 'complete';
}