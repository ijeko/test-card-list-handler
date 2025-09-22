<?php

namespace App\Services\CardProcessor\Spout\Handlers;

use App\Services\CardProcessor\ReaderInterface;
use App\Support\Dtos\RowDataDto;
use Box\Spout\Reader\ReaderInterface as SpoutReaderInterface;

class SpoutXlsxReadHandler implements ReaderInterface
{
    public function __construct(private readonly SpoutReaderInterface $reader)
    {
    }

    public function read(?int $chunk = null): \Generator
    {
        foreach ($this->reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowData = $row->toArray();
                yield new RowDataDto(
                    $rowData[0],
                    $rowData[1],
                    $rowData[2]
                );
            }
        }
    }
}
