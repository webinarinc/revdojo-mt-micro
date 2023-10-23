<?php

namespace Revdojo\MT\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use PDO;
use Revdojo\MT\Console\RevdojoMTInstall;
use Revdojo\MT\Providers\DatabaseServiceProvider;
use Revdojo\MT\Middleware\CheckForMaintenance;

class RevdojoMTServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RevdojoMTInstall::class,
            ]);
        }
        $this->app->register(DatabaseServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app['router']->pushMiddlewareToGroup('web', CheckForMaintenance::class);
        $this->app['router']->pushMiddlewareToGroup('api', CheckForMaintenance::class);

    }
}
