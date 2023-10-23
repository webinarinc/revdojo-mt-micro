<?php

namespace Revdojo\MT\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $connection = 'mysql_base_service';
    protected $fillable = [
        'system_id',
        'friendly_name',
        'system_name',
        'namespace',
        'database_name',
        'database_connection',
        'is_maintenance',
    ];
}
