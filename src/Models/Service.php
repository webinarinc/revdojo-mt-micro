<?php

namespace Revdojo\MT\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Revdojo\MT\Traits\Fillable;

class Service extends Model
{
    use HasFactory,
        Fillable;
    protected $connection = 'mysql_base_service';

}
