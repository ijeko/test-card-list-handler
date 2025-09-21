<?php

namespace App\Services\CardProcessor;

use App\Support\Enums\FileType;

interface PackageAdapterInterface
{
    public function getFileProcessor(FileType $type): BaseFileProcessor;
}
