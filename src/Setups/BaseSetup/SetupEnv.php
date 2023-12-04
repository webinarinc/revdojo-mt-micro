<?php

namespace Revdojo\MT\Setups\BaseSetup;

use Illuminate\Support\Facades\File;
use Revdojo\MT\Setups\CommandResponse;

class SetupEnv 
{
    private $responses = [];
    private $name;
    private $namespace;
    private $sourcePath;
    private $destinationPath;
    private $serviceConfigData;

    public function execute(
        $name, 
        $namespace, 
        $sourcePath, 
        $destinationPath,
        $serviceConfigData
    ) {
        
        $this->name = $name;
        $this->namespace = $namespace;
        $this->sourcePath = $sourcePath;
        $this->destinationPath = $destinationPath;
        $this->serviceConfigData = $serviceConfigData;

        $this->responses = $this->copyEnv();
        $this->responses = $this->addValues();

        return $this->responses;
    }

    protected function copyEnv()
    {
        if (File::exists($this->destinationPath . '/.env')) {
            return CommandResponse::run(
                'error', 
                "$this->destinationPath . '/.env' already exists. ", 
                $this->responses
            );
        }
        

        File::copy($this->sourcePath . '/.env.example', $this->destinationPath . '/.env');
        return CommandResponse::run(
            'info', 
            ".env.example copied and renamed to .env.", 
            $this->responses
        );
    }

    protected function addValues() 
    {
        $envFilePath = base_path($this->destinationPath.'/.env');

        if (!File::isFile($envFilePath)) {
            return CommandResponse::run(
                'error', 
                "The .env file does not exist.", 
                $this->responses
            );
        }
        
        $newEntries = [
            "COMPOSE_PROJECT_NAME={$this->serviceConfigData['COMPOSE_PROJECT_NAME']}",
            "REVOJO_MT_SERVICE_NAME={$this->serviceConfigData['REVOJO_MT_SERVICE_NAME']}",
            "REVOJO_MT_NAMESPACE={$this->serviceConfigData['REVOJO_MT_NAMESPACE']}",
            "REVOJO_MT_DB_NAME={$this->serviceConfigData['REVOJO_MT_DB_NAME']}",
            "DB_CONNECTION={$this->serviceConfigData['DB_CONNECTION']}",
            "DB_HOST={$this->serviceConfigData['DB_HOST']}",
            "DB_PORT={$this->serviceConfigData['DB_PORT']}",
            "DB_DATABASE={$this->serviceConfigData['DB_DATABASE']}",
            "DB_USERNAME={$this->serviceConfigData['DB_USERNAME']}",
            "DB_PASSWORD={$this->serviceConfigData['DB_PASSWORD']}",
            "FORWARD_DB_PORT={$this->serviceConfigData['FORWARD_DB_PORT']}",
            "APP_PORT={$this->serviceConfigData['APP_PORT']}",
            "VITE_PORT={$this->serviceConfigData['VITE_PORT']}",
            "REDIS_HOST={$this->serviceConfigData['REDIS_HOST']}",
            "REDIS_PORT={$this->serviceConfigData['REDIS_PORT']}",
        ];

        $envContents = File::get($envFilePath);

        foreach ($newEntries as $newEntry) {
            $key = explode('=', $newEntry)[0];
            $pattern = "/$key=.*/";
            if (preg_match($pattern, $envContents)) {
                // If the key already exists, replace its value
                $envContents = preg_replace($pattern, $newEntry, $envContents);
            } else {
                // If the key doesn't exist, add it to the .env file
                $envContents .= PHP_EOL . $newEntry;
            }
        }
    
        // Write the modified contents back to the .env file
        File::put($envFilePath, $envContents);

        return CommandResponse::run(
            'info', 
            "Successfully added default env config to $this->destinationPath . '/.env'", 
            $this->responses
        );
    }

}
