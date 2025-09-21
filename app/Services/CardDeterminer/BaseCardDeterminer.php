<?php

namespace App\Services\CardDeterminer;

use App\Support\Dtos\CardDataDto;

abstract class BaseCardDeterminer
{
    abstract public function determine(string $pan): CardDataDto;
}
