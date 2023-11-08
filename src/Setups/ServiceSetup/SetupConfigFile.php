<?php

namespace Revdojo\MT\Setups\ServiceSetup;

use Revdojo\MT\Setups\CommandResponse;
use Revdojo\MT\Setups\RevdojoMTConfig;

class SetupConfigFile 
{
    private $responses = [];

    public function execute()
    {
        if (config('revdojo-mt.service_system_id')) {
            $message = 'revdojo-mt.php config is already exists';
            return $this->responses = CommandResponse::run('error', $message, $this->responses);
        }
        
        (new RevdojoMTConfig)->execute(config_path('revdojo-mt.php'));

        return CommandResponse::run('info', 'Configuration file generated successfully.', $this->responses);
    }

}
