<?php

namespace App\Services\ProcessorFactory;

use App\Services\CardProcessor\PackageAdapterInterface;
use App\Support\Enums\FileType;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ProcessorFactory
{
    public function __construct(private readonly PackageAdapterInterface $packageAdapter, private readonly Request $request) {}

    public function create()
    {
        $file = $this->request->file('file');
        $rawType = $file->getClientMimeType();
        $type = FileType::tryFrom($rawType);

        if (!$type) {
            throw new UnprocessableEntityHttpException('This type is not supported');
        }

        return $this->packageAdapter->getFileProcessor($type);
    }
}
