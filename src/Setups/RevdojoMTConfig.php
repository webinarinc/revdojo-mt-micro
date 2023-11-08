<?php

namespace Revdojo\MT\Setups;

use Illuminate\Support\Facades\File;
use Revdojo\MT\Helpers\ConvertHelper;
use Revdojo\MT\Helpers\GenerateHelper;
use Revdojo\MT\Setups\CreateConfigFile;

class RevdojoMTConfig 
{
    public function execute($destination, $config = null)
    {

        $name = $config?->name ?? env('REVOJO_MT_SERVICE_NAME');
        $friendlyName = ConvertHelper::convertToFriendlyName($name);
        $systemId = $config?->system_id ?? GenerateHelper::generateSystemId('service');
        $namespace = $config?->namespace ?? env('REVOJO_MT_NAMESPACE');
        $folderPath = $config?->folder_path ?? "src_microservices/'.$config->name.'/src";
        $databaseName = $config?->database_name ?? env('REVOJO_MT_DB_NAME');
        $databaseConnection = $config?->database_connection ?? env('REVOJO_MT_DB_NAME');

        $data = [
            'service_system_id' => $systemId,
            'friendly_name' => $friendlyName,
            'system_name' =>  $name,
            'namespace' => $namespace,
            'folder_path' => $folderPath,
            'database_name' => $databaseName,
            'database_connection' => 'mysql_'. $databaseConnection,
        ];

        (new CreateConfigFile)->execute($data, $destination);
    }
}
