<?php

namespace App\Providers;

use App\Services\CardProcessor\PackageAdapterInterface;
use App\Services\CardProcessor\Spout\SpoutAdapter;
use Illuminate\Support\ServiceProvider;

class FileProcessorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(PackageAdapterInterface::class, SpoutAdapter::class);
    }
}
