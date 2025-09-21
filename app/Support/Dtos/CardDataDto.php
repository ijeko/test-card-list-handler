<?php

namespace App\Support\Dtos;

use App\Support\Enums\CardType;

readonly class CardDataDto
{
    public function __construct(
        public CardType $cardType,
        public string   $bank,
    )
    {
    }


}
