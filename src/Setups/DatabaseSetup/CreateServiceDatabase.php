<?php

namespace Revdojo\MT\Setups\DatabaseSetup;

use DB;
use Revdojo\MT\Setups\CommandResponse;
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
