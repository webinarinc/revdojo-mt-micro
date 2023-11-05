<?php

namespace Revdojo\MT\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Revdojo\MT\Helpers\ConvertHelper;
use Revdojo\MT\Helpers\GenerateHelper;
use Revdojo\MT\Models\Service;
use Illuminate\Support\Facades\Artisan;
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
        $this->validateEnvs();

        $this->setupConfigFile();
        $this->setupTenancyConfig();
        $this->setupDomainDriven();
    }

    protected function setupDomainDriven()
    {
        $directoryName = 'src';

        if (!File::exists($directoryName)) {
            File::makeDirectory($directoryName);
            $this->info("Directory '$directoryName' created successfully.");
        } else {
            $this->error("Directory '$directoryName' already exists.");
        }

        $service = Service::where('system_id', config('revdojo-mt.service_system_id'))->first();
        $autoloadData[$service->namespace.'\\'] = 'src';
        $composerJsonPath = base_path('composer.json');
        $composerJson = json_decode(File::get($composerJsonPath), true);
        $composerJson = $this->registerPsr4Autoload($composerJson, $autoloadData);
      
        File::put($composerJsonPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info('Successfully registered all services to composer.json. Please run composer dumpautoload');
    }

    protected function setupTenancyConfig() 
    {
        Artisan::call('vendor:publish', [
            '--tag' => 'tenancy',
        ]);

        $this->info('Successfully added tenancy configuration.');
    }

    protected function registerPsr4Autoload($composerJson, $autoloadData)
    {
        foreach ($autoloadData as $namespace => $path) {
            $composerJson['autoload']['psr-4'][$namespace] = $path;
        }

        return $composerJson;
    }

    protected function setupConfigFile()
    {
        if (config('revdojo-mt.service_system_id')) {
            return $this->error('revdojo-mt.php config is already exists');
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

    protected function validateEnvs()
    {
        if (!(env('REVOJO_MT_SERVICE_NAME') && env('REVOJO_MT_NAMESPACE') && env('REVOJO_MT_DB_NAME')))
        {
            return $this->error('REVOJO_MT_SERVICE_NAME, REVOJO_MT_NAMESPACE, REVOJO_MT_DB_NAME envs are required');
        }
    }
}
