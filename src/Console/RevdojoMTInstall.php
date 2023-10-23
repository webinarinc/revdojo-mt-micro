<?php

namespace Revdojo\MT\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
       dd('revdojo-mt-installing');
    }
}
