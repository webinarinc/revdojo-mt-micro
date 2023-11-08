<?php

namespace Revdojo\MT\Setups\BaseSetup;

use Illuminate\Support\Facades\File;
use Revdojo\MT\Helpers\ConvertHelper;
use Revdojo\MT\Helpers\GenerateHelper;
use Revdojo\MT\Setups\CommandResponse;
use Revdojo\MT\Setups\RevdojoMTConfig;
class SetupDockerFile 
{
    private $responses = [];

    public function execute($serviceConfigData, $destinationPath)
    {
        $composeFilePath = base_path($destinationPath.'/docker-compose.yml');
        $composeContent = file_get_contents($composeFilePath);
        $updatedContent = str_replace('laravel.test', $serviceConfigData['REVOJO_MT_DB_NAME'], $composeContent);
        file_put_contents($composeFilePath, $updatedContent);

        return CommandResponse::run(
            'info', 
            "Docker Compose file updated successfully.", 
            $this->responses
        );
    }
}
