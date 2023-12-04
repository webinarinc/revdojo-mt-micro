<?php

namespace Revdojo\MT\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisSubscribeCommand extends Command
{
    protected $signature = 'redis:subscribe';
    protected $description = 'Subscribe to Redis channels continuously';

    public function handle()
    {
        Redis::subscribe([config('revdojo-mt.database_name')], function ($message) {
            /**
             *  Create a Job that will handle the Event sent from eventbus
             */
        });
    }
}