<?php

namespace Revdojo\MT\Commands;

use Illuminate\Console\Command;
use Revdojo\MT\Helpers\GenerateHelper;

class GenerateSystemId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:system_id {prefix}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate manual system id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->argument('prefix')) {
            return $this->info('--prefix is required');
        }

        return $this->info(GenerateHelper::generateSystemId($this->argument('prefix')));
    }
}
