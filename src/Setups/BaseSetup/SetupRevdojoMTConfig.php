<?php

namespace Revdojo\MT\Setups\BaseSetup;

use Revdojo\MT\Setups\CommandResponse;
use Revdojo\MT\Setups\RevdojoMTConfig;

class SetupRevdojoMTConfig 
{
    private $responses = [];

    public function execute($serviceConfigData, $destinationPath)
    {
        $config = [
            'system_id' => $serviceConfigData['SYSTEM_ID'],
            'name' =>  $serviceConfigData['REVOJO_MT_SERVICE_NAME'],
            'namespace' => $serviceConfigData['REVOJO_MT_NAMESPACE'],
            'database_name' => $serviceConfigData['REVOJO_MT_DB_NAME'],
            'database_connection' => $serviceConfigData['REVOJO_MT_DB_NAME'],
            'app_port' => $serviceConfigData['APP_PORT'],
            'vite_port' => $serviceConfigData['VITE_PORT'],
        ];

        $destination = $destinationPath.'/config/revdojo-mt.php';
        (new RevdojoMTConfig)->execute($destination, $config);
        
        return CommandResponse::run(
            'info', 
            "Configuration file generated successfully", 
            $this->responses
        );
    }
}
