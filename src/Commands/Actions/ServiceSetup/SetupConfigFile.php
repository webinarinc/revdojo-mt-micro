<?php

namespace Revdojo\MT\Commands\Actions\ServiceSetup;

use Revdojo\MT\Commands\Actions\CommandResponse;
use Revdojo\MT\Helpers\ConvertHelper;
use Revdojo\MT\Helpers\GenerateHelper;
use Illuminate\Support\Facades\File;

class SetupConfigFile 
{
    private $responses = [];

    public function execute()
    {
        if (config('revdojo-mt.service_system_id')) {
            $message = 'revdojo-mt.php config is already exists';
            return $this->responses = CommandResponse::run('error', $message, $this->responses);
        }
        
        $friendlyName = ConvertHelper::convertToFriendlyName(env('REVOJO_MT_SERVICE_NAME'));
        $serviceSystemId = GenerateHelper::generateSystemId('service');

        $data = [
            'service_system_id' => $serviceSystemId,
            'friendly_name' => $friendlyName,
            'system_name' =>  env('REVOJO_MT_SERVICE_NAME'),
            'namespace' => env('REVOJO_MT_NAMESPACE'),
            'folder_path' => 'src_microservices/'.env('REVOJO_MT_SERVICE_NAME').'/src',
            'database_name' => env('REVOJO_MT_DB_NAME'),
            'database_connection' => 'mysql_'. env('REVOJO_MT_DB_NAME'),
        ];

        $this->createConfigFile($data);

        return CommandResponse::run('info', 'Configuration file generated successfully.', $this->responses);
    }

    protected function createConfigFile($data)
    {
        // Generate the PHP configuration file content
        $configContents = "<?php\n\nreturn [\n";

        foreach ($data as $key => $value) {
            $configContents .= "    '$key' => '$value',\n";
        }

        $configContents .= "];\n";

        // Path to the configuration file
        $configFilePath = config_path('revdojo-mt.php');

        // Write data to the PHP file
        File::put($configFilePath, $configContents);
    }
}
