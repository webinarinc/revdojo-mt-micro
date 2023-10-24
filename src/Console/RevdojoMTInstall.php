<?php

namespace Revdojo\MT\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Revdojo\MT\Helpers\ConvertHelper;
use Revdojo\MT\Helpers\GenerateHelper;
use Revdojo\MT\Models\Service;
use DB;
use PDO;
use Illuminate\Support\Facades\Config;
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

        $this->setupBaseDB();
        $this->setupAllDB();
        $this->setupConfigFile();
        $this->setupDockerFile();
        $this->setupDomainDriven();
    }

    protected function setupBaseDB()
    {
        $databaseConnection = [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ];
        Config::set("database.connections.mysql_base_service", $databaseConnection);
    }

    protected function setupAllDB()
    {
        $services = Service::all();

        $services->map(function ($service) {
            $databaseConnection = [
                'driver' => 'mysql',
                'url' => env('DATABASE_URL'),
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $service->database_name,
                'username' => env('DB_USERNAME', 'forge'),
                'password' => env('DB_PASSWORD', ''),
                'unix_socket' => env('DB_SOCKET', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ];
            Config::set("database.connections.$service->database_connection", $databaseConnection);
        });

        
        if (config('revdojo-mt.service_system_id')) {
            $myService = $services->where('system_id', config('revdojo-mt.service_system_id'))->first();
            Config::set("database.default", $myService->database_connection);
        }
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


    protected function registerPsr4Autoload($composerJson, $autoloadData)
    {
        foreach ($autoloadData as $namespace => $path) {
            $composerJson['autoload']['psr-4'][$namespace] = $path;
        }

        return $composerJson;
    }

    protected function setupDockerFile()
    {
        $composeFilePath = base_path('docker-compose.yml');
        $composeContent = file_get_contents($composeFilePath);
        $updatedContent = str_replace('laravel.test', env('REVOJO_MT_DB_NAME'), $composeContent);
        file_put_contents($composeFilePath, $updatedContent);
        $this->info('Docker Compose file updated successfully.');
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
