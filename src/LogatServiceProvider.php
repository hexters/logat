<?php

namespace Hexters\Logat;

use Illuminate\Support\ServiceProvider;
use Hexters\Logat\Commands\LangGeneratorCommand;

class LogatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        $this->registerConfig();
        $this->registerCommand();
    }

    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/logat.php', 'logat');
        $this->publishes([
            __DIR__ . '/../config/logat.php' => config_path('logat.php'),
        ]);
    }

    protected function registerCommand()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                LangGeneratorCommand::class
            ]);
        }
    }
}
