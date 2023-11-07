<?php

namespace Revdojo\MT\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $connection = 'mysql_company_service';
    protected $table = 'companies';
    protected $fillable = [
        'system_id',
        'name',
    ];
}
