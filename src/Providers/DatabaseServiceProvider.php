<?php

namespace Revdojo\MT\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Revdojo\MT\Actions\Databases\DatabaseConnection;
use Revdojo\MT\Actions\Databases\SetupServiceDatabase;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        DatabaseConnection::set(env('DB_DATABASE', 'forge'), 'mysql_base_service');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            if (!config('database.connections.mysql_base_service')) {
                return;
            }

            $services = (new SetupServiceDatabase)->execute();
            $this->setupDefault($services);

        } catch (\Throwable $th) {
            return;
        }
    }

    protected function setupDefault($services)
    {
        if (config('revdojo-mt.service_system_id')) {
            $myService = $services->where('system_id', config('revdojo-mt.service_system_id'))->first();
            Config::set("database.default", $myService->database_connection);
        }

        return;
    }
}
