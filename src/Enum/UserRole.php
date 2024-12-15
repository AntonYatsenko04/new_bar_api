<?php

namespace App\Enum;

enum UserRole: string
{
    case manager = 'manager';
    case waiter = 'waiter';
    case cook = 'cook';
}
