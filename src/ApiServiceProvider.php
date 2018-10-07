<?php

namespace StarCitizenWiki\MediawikiApi;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use StarCitizenWiki\MediawikiApi\Api\ApiManager;
use StarCitizenWiki\MediawikiApi\Api\ApiRequestFactory;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $source = realpath($raw = __DIR__.'/../config/mediawiki.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('mediawiki.php')]);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mediawikiapi', ApiRequestFactory::class);
        $this->app->singleton('mediawikiapi.manager', ApiManager::class);
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            'mediawikiapi',
            'mediawikiapi.manager',
        ];
    }
}
