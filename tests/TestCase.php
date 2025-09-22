<?php

namespace Tests;

use App\Support\Helpers\PathHelper;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    protected function prepareXlsx(int $rowsCount, string $path): void
    {
        Storage::createDirectory(PathHelper::uploadPath());
        $data = [
            [
                'id',
                'info',
                'number']
        ];

        $i = 0;

        $rows = [];

        while ($i != $rowsCount) {
            $data[] = [
                $i+1,
                fake()->firstName(),
                fake()->creditCardNumber(),
            ];

            $rows[] = WriterEntityFactory::createRowFromArray($data[$i]);

            $i++;
        }

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($path);
        $writer->addRows($rows);
        $writer->close();
    }
}
