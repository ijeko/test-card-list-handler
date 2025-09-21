<?php

namespace App\Services\CardProcessor;

interface WriterInterface
{
    public function write(array $rows, ?array $heading = null);
}
