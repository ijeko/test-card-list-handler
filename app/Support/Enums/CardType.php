<?php

namespace App\Support\Enums;

enum CardType: string
{
    case Visa = 'visa';
    case Mastercard = 'mastercard';
    case Maestro = 'maestro';
    case UnionPay = 'unionpay';
    case Mir = 'mir';
    case Unknown = 'unknown';
}
