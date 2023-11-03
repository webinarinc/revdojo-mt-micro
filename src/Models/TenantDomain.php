<?php

namespace Revdojo\MT\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantDomain extends Model
{
    protected $connection = 'mysql_tenant_service';

    protected $table = 'tenants_domains';

    protected $fillable = [
        'name',
        'domain',
        'tenant_base_id',
    ];
}
