<?php

namespace Revdojo\MT\Setups\BaseSetup;

use Revdojo\MT\Setups\CommandResponse;

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
