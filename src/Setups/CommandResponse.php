<?php

namespace Revdojo\MT\Setups;


class CommandResponse
{
    public static function run($status, $message, $responses) 
    {
        array_push($responses, [
            'status' => $status,
            'message' => $message
        ]);

        return $responses;
    }
}
