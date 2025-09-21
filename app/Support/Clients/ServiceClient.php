<?php

namespace App\Support\Clients;

use App\Support\Helpers\SignatureHelper;
use GuzzleHttp\Client;

class ServiceClient extends BaseClient
{
    protected function setClient(): void
    {
        $this->headers = [
            'Accept' => 'application/json',
        ];

        $this->client = new Client();
    }

    public function sendProcessed(string $url, array $body, array $files): array
    {
        $signature = SignatureHelper::generate($url);
        $this->headers = array_merge($this->headers, [
            'X-Signature' => $signature,
            'Content-Type' => 'multipart/form-data',
        ]);

        return $this->post($url, $body, $files);
    }
}
