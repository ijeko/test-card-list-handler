<?php

use App\Providers\CardDeterminerServiceProvider;
use App\Providers\FileProcessorServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    FileProcessorServiceProvider::class,
    CardDeterminerServiceProvider::class,
];
