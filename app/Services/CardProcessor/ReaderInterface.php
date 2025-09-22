<?php

namespace App\Services\CardProcessor;

use App\Support\Dtos\RowDataDto;

interface ReaderInterface
{
    /**
     * @return \Generator<int, RowDataDto>
     */
    public function read(?int $chunk = null): \Generator;
}
