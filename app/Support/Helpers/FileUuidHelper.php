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
        $fileName = $uuid->toString().'.'.$file->getClientOriginalExtension();
        $file->storeAs(PathHelper::uploadPath().$fileName);

        Cache::put($fileName, $url);

        return $fileName;
    }

    public static function delete(string $fileName): void
    {
        Cache::forget($fileName);
        $path = Storage::path('uploads/' . $fileName);

        File::delete($path);
    }
}
