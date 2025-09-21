<?php

namespace App\Support\Clients;

use GuzzleHttp\Client;

class BinListClient extends BaseClient
{
    public function getCardInfo(string $key): array
    {
        try {
            $response = $this->client->request('GET', $key);

            if ($response->getStatusCode() === 429) {
                throw new \Exception('Too many requests');
            }

            $result = json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $exception) {
            $result = [];
        }

        return $result;
    }

    protected function setClient(): void
    {
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Accept-Version' => '3',
        ];

        $this->client = new Client([
            'base_uri' => config('services.binlist.url'),
            'headers' => $this->headers,
        ]);
    }
}
