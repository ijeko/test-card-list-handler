<?php

namespace App\Jobs;

use App\Services\CardProcessor\BaseFileProcessor;
use App\Support\Helpers\SignatureHandler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ProcessCardsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly BaseFileProcessor $processor, private readonly string $fileUuid)
    {
        //
    }

    public function handle(): void
    {
        if (!Storage::exists('uploads/processed')) {
            Storage::createDirectory('uploads/processed');
        }

        $chunkSize = $this->processor::chunkSize();

        $readPath = Storage::path('uploads/' . $this->fileUuid);
        $writePath = Storage::path('uploads/processed/' . $this->fileUuid);

        $reader = $this->processor::setReader($readPath);
        $writer = $this->processor::setWriter($writePath);

        $chunk = [];

        try {
            foreach ($reader->read() as $row) {
                $chunk[] = $row;

                if (count($chunk) == $chunkSize) {
                    $writer->write($chunk);
                    $chunk = [];
                }
            }

            $message = 'Success';
        } catch (\Exception $exception) {
            $message = 'File process error: ' . $exception->getMessage();
        }

        $this->processor::close();

        SendProcessedFileJob::dispatch($writePath, $message);
    }
}
