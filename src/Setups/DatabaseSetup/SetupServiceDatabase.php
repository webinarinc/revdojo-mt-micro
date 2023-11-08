<?php

namespace Revdojo\MT\Setups\DatabaseSetup;

use Revdojo\MT\Models\Service;
use Revdojo\MT\Setups\DatabaseSetup\DatabaseConnection;

class SetupServiceDatabase 
{
    public function execute()
    {
        $services = Service::all();
        $services->map(function ($service) {
            DatabaseConnection::set($service->database_name, $service->database_connection);
        });

        return $services;
    }
}
