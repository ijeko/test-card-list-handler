<?php

namespace App\Services\CardDeterminer;

use App\Support\Clients\BinListClient;
use App\Support\Dtos\CardDataDto;
use App\Support\Enums\CardType;
use Illuminate\Support\Facades\Cache;

class BinlistCardDeterminer extends BaseCardDeterminer
{
    public function __construct(private readonly BinListClient $client)
    {
    }

    public function determine(string $pan): CardDataDto
    {
        $key = substr($pan, 0, min(8, strlen($pan)));

        $cardData = Cache::remember($key, 3600*24, function () use ($key) {
            return $this->client->getCardInfo($key);
        });

        if (is_array($cardData)) {
            $scheme = $cardData['scheme'] ?? 'unknown';
            $bank = $cardData['bank']['name'] ?? 'unknown';
            $cardType = CardType::tryFrom($scheme);

            $cardDto = new CardDataDto($cardType, $bank);
        } else {
            $cardDto = new CardDataDto(CardType::Unknown, 'unknown');
        }

        return $cardDto;
    }
}
