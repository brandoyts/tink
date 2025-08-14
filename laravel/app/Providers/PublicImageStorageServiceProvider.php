<?php

namespace App\Providers;

use App\Contracts\ImageStorageContract;
use App\Services\PublicImageStorageService;
use Illuminate\Support\ServiceProvider;

class PublicImageStorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ImageStorageContract::class, PublicImageStorageService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
