<?php

namespace app\Services\CardProcessor\Spout\Handlers;


use App\Services\CardProcessor\ReaderInterface;
use Box\Spout\Reader\ReaderInterface as SpoutReaderInterface;

class SpoutXlsxReadHandler implements ReaderInterface
{
    public function __construct(private readonly SpoutReaderInterface $reader)
    {
    }

    public function read(?int $chunk = null): mixed
    {
        foreach ($this->reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                yield $row->toArray();
            }
        }
    }
}
