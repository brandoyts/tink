<?php

namespace App\Providers;

use App\Services\AI\ImageGeneratorService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Client\Factory as HttpFactory;

class ImageGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageGeneratorService::class, function($app) {
            return new ImageGeneratorService(
                $app->make(HttpFactory::class),
                config("services.gemini.api_key"),
                config("services.gemini.image_generator_api_url")
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
