<?php

namespace Revdojo\MT\Commands;

use Illuminate\Console\Command;
use Revdojo\MT\Setups\ServiceSetup\SetupConfigFile;
use Revdojo\MT\Setups\ServiceSetup\SetupDomainAutoload;
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

    protected function validateEnvs()
    {
        if (!(env('REVOJO_MT_SERVICE_NAME') && env('REVOJO_MT_NAMESPACE') && env('REVOJO_MT_DB_NAME')))
        {
            return $this->error('REVOJO_MT_SERVICE_NAME, REVOJO_MT_NAMESPACE, REVOJO_MT_DB_NAME envs are required');
        }
    }

    protected function setupConfigFile()
    {
        $messages = (new SetupConfigFile)->execute();
        $this->messages($messages);
    }

    protected function setupTenancyConfig() 
    {
        Artisan::call('vendor:publish', [
            '--tag' => 'tenancy',
        ]);

        $this->info('Successfully added tenancy configuration.');
    }

    protected function setupDomainDriven()
    {
        $messages = (new SetupDomainAutoload)->execute();
        $this->messages($messages);
    }

    protected function messages($messages) 
    {
        foreach ($messages as $message) {
            $this->{$message['status']}($message['message']);
        }
    }
}
