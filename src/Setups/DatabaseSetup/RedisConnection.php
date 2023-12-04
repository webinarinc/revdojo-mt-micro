<?php

namespace Revdojo\MT\Setups\DatabaseSetup;

use Illuminate\Support\Facades\Config;

class RedisConnection 
{
    public static function set($name)
    {
        $databaseConnection = [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ];

        Config::set("database.redis.$name", $databaseConnection);
    }
}
