<?php

namespace App\Services\ProcessorFactory;

use App\Services\CardProcessor\PackageAdapterInterface;
use App\Support\Enums\FileType;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ProcessorFactory
{
    public function __construct(private readonly PackageAdapterInterface $packageAdapter) {}

    public function create()
    {
        $file = request()->file('file');
        $rawType = $file->getClientMimeType();
        $type = FileType::tryFrom($rawType);

        if (!$type) {
            throw new UnprocessableEntityHttpException('This type is not supported');
        }

        return $this->packageAdapter->getFileProcessor($type);
    }
}
