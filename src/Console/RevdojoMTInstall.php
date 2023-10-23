<?php

namespace Revdojo\MT\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Revdojo\MT\Helper\ConvertHelper;
use Revdojo\MT\Helper\GenerateHelper;
class RevdojoMTInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revdojo-mt:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installing the Revdojo MT Microservice';

    /**
     * Execute the console command.
     */
    public function handle()
    {
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

        $this->info('Configuration file generated successfully.');

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
