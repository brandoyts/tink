<?php

namespace App\Providers;

use App\Contracts\ImageValidatorContract;
use App\Services\ImageValidatorService;
use Illuminate\Support\ServiceProvider;

class ImageValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ImageValidatorContract::class, ImageValidatorService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
