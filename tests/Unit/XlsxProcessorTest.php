<?php

namespace Tests\Unit;

use App\Services\CardProcessor\Spout\SpoutXlsxProcessor;
use App\Support\Dtos\RowDataDto;
use Tests\TestCase;

class XlsxProcessorTest extends TestCase
{
    private string $path;

    protected function setUp(): void
    {
        parent::setUp();
        $this->path = storage_path('uploaded.xlsx');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unlink($this->path);
    }

    public function testReadingFromIteratorAsDto(): void
    {
        $rowsCount = 10;
        $this->prepareXlsx($rowsCount, $this->path);

        $processor = new SpoutXlsxProcessor();
        $reader = $processor::setReader($this->path);

        $readData = [];

        foreach ($reader->read() as $rows) {
            $readData[] = $rows;
        }

        $this->assertEquals($rowsCount, count($readData));
        $this->assertInstanceOf(RowDataDto::class, $readData[0]);
    }


}
