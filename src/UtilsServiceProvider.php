<?php

namespace Melit\Utils;

use Illuminate\Support\ServiceProvider;

class UtilsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->loadFactoriesFrom(__DIR__ . '/factories');
        $this->loadTranslationsFrom(__DIR__ . '/translations', 'utils');
        $this->loadViewsFrom(__DIR__ . '/views', 'utils');

        $publishes = [
            __DIR__ . '/views'        => resource_path('views/melit/utils'),
            __DIR__ . '/translations' => resource_path('lang/melit/utils'),
            __DIR__ . '/assets'       => public_path('vendor/melit/utils'),
        ];

        var_dump($publishes);

        $this->publishes($publishes);


        $this->app->make('Melit\Utils\SettingsController');
    }
}
