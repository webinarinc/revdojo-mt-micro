<?php

namespace Revdojo\MT;

use Illuminate\Support\ServiceProvider;
use Revdojo\MT\Commands\GenerateSystemId;
class RevdojoMTServiceProvider extends ServiceProvider
{
     protected function runCommands() 
     {
         $this->commands([
             GenerateSystemId::class,
         ]);
     }

    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->runCommands();
        }
    }

    public function boot(): void
    {
        $this->runCommands();
    }
}
