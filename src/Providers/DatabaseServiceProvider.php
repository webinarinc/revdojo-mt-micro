<?php

namespace Revdojo\MT\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Revdojo\MT\Setups\DatabaseSetup\DatabaseConnection;
use Revdojo\MT\Setups\DatabaseSetup\RedisConnection;
use Revdojo\MT\Setups\DatabaseSetup\SetupServiceDatabase;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        DatabaseConnection::set(env('DB_DATABASE', 'forge'), 'mysql_base_service');
        RedisConnection::set('subscribe');
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
