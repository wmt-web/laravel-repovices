<?php

namespace Laravel\Repovices;

use Laravel\Repovices\Console\RepoviceConsole;

class RepovicesServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/repovice.php';
        $this->mergeConfigFrom($configPath, 'repovice');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/repovice.php';
        $this->publishConfig($configPath);

        if ($this->app->runningInConsole()) {
            $this->commands([
                RepoviceConsole::class,
            ]);
        }
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('repovice.php');
    }

    /**
     * Publish the config file
     *
     * @param  string $configPath
     */
    protected function publishConfig($configPath)
    {
        $this->publishes([$configPath => $this->getConfigPath()], 'config');
    }
}