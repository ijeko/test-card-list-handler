<?php

namespace App\Services\CardProcessor\Spout;

use App\Services\CardProcessor\BaseFileProcessor;
use App\Services\CardProcessor\ReaderInterface;
use App\Services\CardProcessor\Spout\Handlers\SpoutXlsxReadHandler;
use App\Services\CardProcessor\Spout\Handlers\SpoutXlsxWriteHandler;
use App\Services\CardProcessor\WriterInterface;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\ReaderInterface as SpoutReaderInterface;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface as SpoutWriterInterface;

class SpoutXlsxProcessor extends BaseFileProcessor
{
    private static SpoutWriterInterface $writer;
    private static SpoutReaderInterface $reader;

    public static function setWriter(string $path): WriterInterface
    {
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($path);

        self::$writer = $writer;

       return new SpoutXlsxWriteHandler($writer);
    }

    public static function setReader(string $path): ReaderInterface
    {
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($path);

        self::$reader = $reader;

        return new SpoutXlsxReadHandler($reader);
    }

    public static function close(): void
    {
        if (isset(static::$writer)) {
            static::$writer->close();
        }

        if (isset(static::$reader)) {
            static::$reader->close();
        }
    }
}
