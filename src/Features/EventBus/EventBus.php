<?php

namespace Revdojo\MT\Features\EventBus;

use Illuminate\Support\Facades\Redis;
use Revdojo\MT\Models\EventBusLog;

abstract class EventBus
{

    public static function publish(
        $origin,
        $destination,
        $type,
        Array $payload,
    ) {
        $eventBusData = [
            'origin' => $origin,
            'destination' => $destination,
            'type' => $type,
            'payload' => json_encode($payload),
        ];

        $eventBugLog = new EventBusLog;
        $eventBugLog->fill($eventBusData);
        $eventBugLog->save();
        $eventBugLog->refresh();

        SELF::redisPublish($destination, $eventBugLog);
    }

    public static function redisPublish($destination, $eventBus) 
    {
        Redis::publish($destination, $eventBus);
    }

    public static function success($eventBus) 
    {
        $eventBus->status = 'success';
        $eventBus->save();
        $eventBus->refresh();
    }

    public static function failed($eventBus, $exception) 
    {
        $eventBus->exception = $exception;
        $eventBus->status = 'failed';
        $eventBus->save();
        $eventBus->refresh();
    }


    public static function retry($eventBugLog) 
    {
        SELF::redisPublish($eventBugLog->destination, $eventBugLog);
    }
}