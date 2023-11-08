<?php

namespace Revdojo\MT;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use PDO;
use Revdojo\MT\Commands\RevdojoMTInstall;
use Revdojo\MT\Providers\DatabaseServiceProvider;
use Revdojo\MT\Middleware\CheckForMaintenance;
use Revdojo\MT\Providers\TenancyServiceProvider;
use Revdojo\MT\Providers\SubDomainServiceProvider;
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

        try {
            $this->app->register(DatabaseServiceProvider::class);
            $this->app->register(TenancyServiceProvider::class);
            $this->app->register(SubDomainServiceProvider::class);
        } catch (\Throwable $th) {
            return;
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->commands([
            RevdojoMTInstall::class,
        ]);

        $this->app['router']->pushMiddlewareToGroup('web', CheckForMaintenance::class);
        $this->app['router']->pushMiddlewareToGroup('api', CheckForMaintenance::class);

        $this->publishes([
            __DIR__ . '/../assets/config.php' => config_path('tenancy.php'),
        ], 'tenancy');
    }
}
