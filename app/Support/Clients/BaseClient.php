<?php

namespace App\Support\Clients;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

abstract class BaseClient
{
    protected Client $client;
    protected array $headers = [];

    public function __construct()
    {
        $this->setClient();
    }

    abstract protected function setClient(): void;

    public function post(string $method, array $data, ?array $files = []): array
    {
        Log::debug(__METHOD__, [
            'method' => $method,
            'data' => $data,
        ]);

        return ['message' => 'Success'];
    }
}
