<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateSecret extends Command
{
    protected $signature = 'key:generate-secret';
    protected $description = 'Generate secret key';

    public function handle(): void
    {
        $secret = bin2hex(random_bytes(32));

        $filePath = "secret/service.key";

        Storage::disk('local')->put($filePath, $secret);

        $this->line("Secret key: {$secret}");
    }
}
