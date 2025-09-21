<?php

namespace App\Services\CardProcessor;

interface ReaderInterface
{
    public function read(?int $chunk = null): \Traversable;
}
