<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateSignature extends Command
{
    protected $signature = 'key:signature';
    protected $description = 'Generate signature';

    public function handle(): void
    {
        $backUrl = 'https://back-url.com/webhook/card-handler';
        $method   = 'cards/process';

        $filePath = "secret/service.key";

        if (!Storage::disk('local')->exists($filePath)) {
            $this->error("Generate secret key first");

            return;
        }

        $secret = trim(Storage::disk('local')->get($filePath));

        $string = implode('.', [$method, $backUrl]);

        $signature = base64_encode(hash_hmac('sha256', $string, $secret, true));

        $this->line("X-Url: {$backUrl}");
        $this->line("X-Signature: {$signature}");
    }
}
