<?php

namespace LiveOficial\Pix;

use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    public function register()
    {
        $this->app->singleton(PixService::class, function ($app) {
            $config = $app->make('config')->get('pix');
            $receiver = new Receiver($config);
            $api = new Api($receiver);
            return new PixService($api);
        });
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'pix');
    }
}
