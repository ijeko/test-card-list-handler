<?php

namespace App\Jobs;

use App\Support\Clients\ServiceClient;
use App\Support\Helpers\FileUuidHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SendProcessedFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(private readonly string $path, private readonly string $message)
    {
    }

    public function handle(): void
    {
        $client = new ServiceClient();

        if (File::exists($this->path)) {
            $files = [];
        } else {
            $files = [File::exists($this->path)];
        }

        $uuid = Str::afterLast($this->path, '/');
        $url = Cache::get($uuid);

        $body = [
            'message' => $this->message,
        ];

        try {
            $client->sendProcessed($url, $body, $files);
        } catch (\Exception $e) {
            throw $e;
        }

        File::delete($this->path);
        FileUuidHelper::delete($uuid);
    }

    public function failed(): void
    {
        $uuid = Str::afterLast($this->path, '/');
        FileUuidHelper::delete($uuid);
        File::delete($this->path);
    }
}
