<?php

namespace Revdojo\MT\Setups\Databases;

use Revdojo\MT\Models\Service;

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
