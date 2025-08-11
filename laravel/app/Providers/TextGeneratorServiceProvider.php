<?php

namespace App\Providers;

use App\Services\AI\TextGeneratorService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Client\Factory as HttpFactory;

class TextGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TextGeneratorService::class, function($app) {
            return new TextGeneratorService(
                $app->make(HttpFactory::class), 
                config("services.gemini.api_key"), 
                config("services.gemini.text_generator_api_url")
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
