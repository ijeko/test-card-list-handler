<?php

namespace App\Services\CardProcessor\Spout;

use App\Services\CardProcessor\BaseFileProcessor;
use App\Services\CardProcessor\PackageAdapterInterface;
use App\Support\Enums\FileType;

class SpoutAdapter implements PackageAdapterInterface
{

    public function getFileProcessor(FileType $type): BaseFileProcessor
    {
        return match ($type) {
            FileType::Xlsx => new SpoutXlsxProcessor()
        };
    }
}
