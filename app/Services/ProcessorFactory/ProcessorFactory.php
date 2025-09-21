<?php

namespace App\Services\ProcessorFactory;

use App\Services\CardProcessor\PackageAdapterInterface;
use App\Support\Enums\FileType;

class ProcessorFactory
{
    public function __construct(private readonly PackageAdapterInterface $packageAdapter) {}

    public function create()
    {
        $file = request()->file('file');
        $rawType = $file->getClientMimeType();
        $type = FileType::tryFrom($rawType);

        if (!$type) {
            throw new \Exception('This type is not supported');
        }

        return $this->packageAdapter->getFileProcessor($type);
    }
}
