<?php

namespace App\Support\Dtos;

class RowDataDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $info,
        public readonly string $pan,
        public ?string $cardType = '',
    )
    {
    }

    public function toArray(): array
    {
        return [
            $this->id,
            $this->info,
            $this->pan,
            $this->cardType,
        ];
    }

    public function isHeading(): bool
    {
        return $this->id === 'id';
    }
}
