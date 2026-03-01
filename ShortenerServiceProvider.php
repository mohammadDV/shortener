<?php

namespace Mohammaddv\Shortener;

use Illuminate\Support\ServiceProvider;
use Mohammaddv\Shortener\Contracts\ShortLinkServiceInterface;
use Mohammaddv\Shortener\Contracts\UrlValidatorInterface;
use Mohammaddv\Shortener\Services\ShortLinkService;
use Mohammaddv\Shortener\Services\UrlValidatorService;

/**
 * Service provider for the shortener package
 */
class ShortenerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/shortener.php',
            'shortener'
        );

        $this->app->bind(
            ShortLinkServiceInterface::class,
            ShortLinkService::class
        );

        $this->app->bind(
            UrlValidatorInterface::class,
            UrlValidatorService::class
        );
    }

    /**
     * Bootstrap the service provider
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->publishes([
            __DIR__.'/config/shortener.php' =>
                config_path('shortener.php'),
        ], 'shortener-config');
    }
}