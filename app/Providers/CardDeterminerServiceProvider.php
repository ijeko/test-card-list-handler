<?php

namespace App\Providers;

use App\Services\CardDeterminer\BaseCardDeterminer;
use App\Services\CardDeterminer\SimpleCardDeterminer;
use Illuminate\Support\ServiceProvider;

class CardDeterminerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(BaseCardDeterminer::class, SimpleCardDeterminer::class);
    }
}
