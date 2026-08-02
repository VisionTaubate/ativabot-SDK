<?php

namespace AtivaBot\Laravel;

use Illuminate\Support\ServiceProvider;
use AtivaBot\Client;
use AtivaBot\Config;

class AtivaBotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrapping dos serviços de aplicação.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/ativabot.php' => $this->app->configPath('ativabot.php'),
            ], 'ativabot-config');
        }
    }

    /**
     * Registro dos bindings no container de serviços.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/ativabot.php', 'ativabot');

        $this->app->singleton(Client::class, function ($app) {
            $config = new Config(
                (string) $app['config']->get('ativabot.api_id', ''),
                (string) $app['config']->get('ativabot.jwt_token', ''),
                (int) $app['config']->get('ativabot.timeout', 30),
                $app['config']->get('ativabot.base_url')
            );

            return new Client($config);
        });

        $this->app->alias(Client::class, 'ativabot');
    }
}
