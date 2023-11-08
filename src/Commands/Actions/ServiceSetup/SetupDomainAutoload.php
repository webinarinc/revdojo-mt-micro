<?php

namespace Revdojo\MT\Commands\Actions\ServiceSetup;

use Illuminate\Support\Facades\File;
use Revdojo\MT\Actions\RegisterAutoload;
use Revdojo\MT\Models\Service;
use Revdojo\MT\Commands\Actions\CommandResponse;
class SetupDomainAutoload 
{
    private $responses = [];

    public function execute()
    {
        $directoryName = 'src';
        $successMessage = "Directory '$directoryName'";

        if (!File::exists($directoryName)) {
            File::makeDirectory($directoryName);
            $message = $successMessage. ' created successfully.';
            $this->responses = CommandResponse::run('info', $message, $this->responses);
        } else {
            $message = $successMessage. ' already exists.';
            $this->responses = CommandResponse::run('error', $message, $this->responses);
        }

        $service = Service::where('system_id', config('revdojo-mt.service_system_id'))->first();
        $autoloadData[$service->namespace.'\\'] = 'src';

        $autoloadResponse = (new RegisterAutoload)->execute($autoloadData);

        return CommandResponse::run('info', $autoloadResponse,  $this->responses);
    }
}
