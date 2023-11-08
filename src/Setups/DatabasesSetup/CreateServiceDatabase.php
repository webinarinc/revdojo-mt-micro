<?php

namespace Revdojo\MT\Setups\DatabasesSetups;

use DB;
use Illuminate\Support\Facades\File;
use Revdojo\MT\Helpers\ConvertHelper;
use Revdojo\MT\Helpers\GenerateHelper;
use Revdojo\MT\Setups\CommandResponse;
use Revdojo\MT\Setups\RevdojoMTConfig;
class CreateServiceDatabase 
{
    private $responses = [];

    protected function execute($config)
    {
        $databaseName = $config['REVOJO_MT_DB_NAME'];

        $result = DB::select("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?", [$databaseName]);

        if (!empty($result)) {
            return CommandResponse::run(
                'error', 
                "Database already exist.", 
                $this->responses
            );
        }
        
        $charset = config('database.connections.mysql.charset');
        $collation = config('database.connections.mysql.collation');

        DB::statement("CREATE DATABASE $databaseName CHARACTER SET $charset COLLATE $collation");

        return CommandResponse::run(
            'info', 
            "Database $databaseName created successfully.",
            $this->responses
        );
    }
}
