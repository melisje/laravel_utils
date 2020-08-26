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
      $this->loadRoutesFrom(__DIR__.'/routes.php');
      $this->loadMigrationsFrom(__DIR__.'/migrations');
      $this->loadViewsFrom(__DIR__.'/views', 'utils');
      $this->publishes([
          __DIR__.'/views' => base_path('resources/views'),
      ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Melit\Utils\SettingsController');
    }
}
