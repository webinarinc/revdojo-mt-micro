<?php

namespace Revdojo\MT\Setups\BaseSetup;

use Illuminate\Support\Facades\File;
use Revdojo\MT\Setups\CommandResponse;
class SetupMicroserviceFolder 
{
    private $responses = [];

    public function execute(
        $name, 
        $namespace, 
        $sourcePath, 
        $destinationPath,
        $serviceConfigData
    ) {

        if (File::exists($destinationPath)) {
            $this->responses = (new SetupEnv)->execute(
                $name, 
                $namespace, 
                $sourcePath, 
                $destinationPath, 
                $serviceConfigData
            );

            return CommandResponse::run(
                'error', 
                "The destination folder already exists. Please choose a different service name.", 
                $this->responses
            );
        }

        if (!File::copyDirectory($sourcePath, $destinationPath)) {
            return CommandResponse::run(
                'error', 
                "Failed to copy and move the folder.", 
                $this->responses
            );
        }

        $this->responses = (new SetupEnv)->execute(
            $name, 
            $namespace, 
            $sourcePath, 
            $destinationPath, 
            $serviceConfigData
        );

        return CommandResponse::run(
            'info', 
            "Folder copied and moved successfully!", 
            $this->responses
        );
    }
}
