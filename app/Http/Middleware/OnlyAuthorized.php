<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class OnlyAuthorized
{
    public function handle(Request $request, Closure $next): Response
    {
        $backUrl = $request->header('X-Url');
        $signature = $request->header('X-Signature');
        $method = Str::after($request->path(), '/');

        $secret = trim(Storage::disk('local')->get("secret/service.key"));

        $string = implode('.', [
            $method,
            $backUrl,
        ]);

        $expected = base64_encode(hash_hmac('sha256', $string, $secret, true));

        if (!$signature || !hash_equals($expected, $signature)) {
            return response('Invalid signature', 403);
        }

        return $next($request);
    }
}
