<?php

namespace Revdojo\MT\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Revdojo\MT\Traits\Fillable;

class Company extends Model
{
    use HasFactory,
        Fillable;

    protected $connection = 'mysql_company_service';
    protected $table = 'companies';
}
