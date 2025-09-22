<?php

namespace App\Support\Helpers;

class PathHelper
{
    public static function uploadPath(): string
    {
        return config('file-processor.path') . '/';
    }

    public static function processedPath(): string
    {
        return config('file-processor.path').'/processed/';
    }
}
