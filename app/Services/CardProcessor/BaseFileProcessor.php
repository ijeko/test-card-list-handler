<?php

namespace App\Services\CardProcessor;


abstract class BaseFileProcessor
{
    abstract public static function setWriter(string $path): WriterInterface;
    abstract public static function setReader(string $path): ReaderInterface;
    abstract public static function close();

    public static function chunkSize(): int
    {
        return config('file-processor.chunk_size');
    }
}
