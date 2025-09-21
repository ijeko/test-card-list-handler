<?php

namespace App\Support\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUuidHelper
{
    public static function storeWithUuid(UploadedFile $file, string $url): string
    {
        $uuid = Str::uuid();

        $file->storeAs('uploads/'.$uuid);

        Cache::put($uuid, $url);

        return $uuid;
    }

    public static function delete(string $uuid): void
    {
        Cache::forget($uuid);
        $path = Storage::path('uploads/' . $uuid);

        File::delete($path);
    }
}
