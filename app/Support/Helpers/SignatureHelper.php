<?php

namespace App\Support\Helpers;

use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class SignatureHelper
{
    public static function generate(string $url): string
    {
        $method = trim(parse_url($url)['path'], '/');

        $filePath = "secret/service.key";

        if (!Storage::disk('local')->exists($filePath)) {
            throw new Exception('Secret key not found');
        }

        $secret = trim(Storage::disk('local')->get($filePath));

        $string = implode('.', [$method, $url]);

        $signature = base64_encode(hash_hmac('sha256', $string, $secret, true));

        return $signature;
    }
}
