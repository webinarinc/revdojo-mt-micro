<?php

namespace Revdojo\MT\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Revdojo\MT\Traits\Fillable;

class EventBusLog extends Model
{
    use HasFactory,
        Fillable;
    protected $connection = 'mysql_base_service';
    protected $table = 'event_bus_logs';

    protected static function boot()
    {
        parent::boot();
        static::bootFillable();
    }
}
