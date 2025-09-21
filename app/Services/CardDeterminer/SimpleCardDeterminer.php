<?php

namespace App\Services\CardDeterminer;

use App\Support\Dtos\CardDataDto;
use App\Support\Enums\CardType;

class SimpleCardDeterminer extends BaseCardDeterminer
{
    public function determine(string $pan): CardDataDto
    {
        $cardNumber = preg_replace('/\D/', '', $pan);
        $length = strlen($cardNumber);

        if (in_array($length, [13, 16, 19], true) && preg_match('/^4[0-9]{12}(?:[0-9]{3})?(?:[0-9]{3})?$/', $cardNumber)) {
            $cardType = CardType::Visa;
        }

        if ($length === 16 && preg_match('/^(5[1-5][0-9]{14}|(222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12})$/', $cardNumber)) {
            $cardType = CardType::Mastercard;
        }

        if ($length >= 12 && $length <= 19 && preg_match('/^(50|5[6-9]|6[0-9])[0-9]{10,17}$/', $cardNumber)) {
            $cardType = CardType::Maestro;
        }

        if ($length >= 16 && $length <= 19 && preg_match('/^62[0-9]{14,17}$/', $cardNumber)) {
            $cardType = CardType::UnionPay;
        }

        if ($length >= 16 && $length <= 19 && preg_match('/^220[0-4][0-9]{0,15}$/', $cardNumber)) {
            $cardType = CardType::Mir;
        }

        if (!isset($cardType)) {
            $cardType = CardType::Unknown;
        }

        return new CardDataDto($cardType, 'unknown');
    }
}
