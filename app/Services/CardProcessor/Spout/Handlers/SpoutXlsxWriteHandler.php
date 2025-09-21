<?php

namespace app\Services\CardProcessor\Spout\Handlers;

use App\Services\CardProcessor\WriterInterface;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface as SpoutWriterInterface;

class SpoutXlsxWriteHandler implements WriterInterface
{
    public function __construct(private readonly SpoutWriterInterface $writer)
    {
    }

    public function write(array $rows, ?array $heading = null): void
    {
        if ($heading) {
            $headerRow = WriterEntityFactory::createRowFromArray($heading);
            $this->writer->addRow($headerRow);
        }

        $spoutRows = [];

        foreach ($rows as $row) {
            $spoutRows[] = WriterEntityFactory::createRowFromArray($row);
        }

        if (!empty($spoutRows)) {
            $this->writer->addRows($spoutRows);
        }
    }
}
