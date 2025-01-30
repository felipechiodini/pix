<?php

namespace LiveOficial\Pix;

use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    public function register()
    {
        $this->app->singleton(\LiveOficial\Pix\PixService::class, function ($app) {
            $config = $app->make('config')->get('pix');
            return new \LiveOficial\Pix\PixService($config);
        });
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'pix');
    }
}
