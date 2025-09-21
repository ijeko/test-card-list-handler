<?php

namespace App\Console\Commands;

use App\Support\Helpers\SignatureHelper;
use Illuminate\Console\Command;

class GenerateSignature extends Command
{
    protected $signature = 'key:signature';
    protected $description = 'Generate signature';

    public function handle(): void
    {
        $backUrl = 'https://back-url.com/webhook/card-handler';

        $signature = SignatureHelper::generate($backUrl);

        $this->line("X-Url: {$backUrl}");
        $this->line("X-Signature: {$signature}");
    }
}
