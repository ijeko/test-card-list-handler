<?php

namespace App\Jobs;

use App\Services\CardDeterminer\BaseCardDeterminer;
use App\Services\CardProcessor\BaseFileProcessor;
use App\Support\Helpers\PathHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
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
        /** @var BaseCardDeterminer $cardDataDeterminer */
        $cardDataDeterminer = app(BaseCardDeterminer::class);

        if (!Storage::exists(PathHelper::processedPath())) {
            Storage::createDirectory(PathHelper::processedPath());
        }

        $chunkSize = $this->processor::chunkSize();

        $readPath = Storage::path(PathHelper::uploadPath() . $this->fileUuid);
        $writePath = Storage::path(PathHelper::processedPath() . $this->fileUuid);

        $reader = $this->processor::setReader($readPath);
        $writer = $this->processor::setWriter($writePath);

        $chunk = [];

        try {
            foreach ($reader->read() as $row) {
                if ($row->isHeading()) {
                    $row->cardType = 'Card type / bank';
                } else {
                    $cardData = $cardDataDeterminer->determine($row->pan);
                    $row->cardType = $cardData->cardType->value . '/' . $cardData->bank;
                }

                $chunk[] = $row->toArray();

                if (count($chunk) == $chunkSize) {
                    $writer->write($chunk);
                    $chunk = [];
                }
            }

            if (!empty($chunk)) {
                $writer->write($chunk);
            }

            $message = 'Success';
        } catch (\Exception $exception) {
            Log::error(__METHOD__, ['error' => $exception->getMessage()]);
            $message = 'File process error: ' . $exception->getMessage();
        }

        $this->processor::close();

        SendProcessedFileJob::dispatch($writePath, $message);
    }
}
