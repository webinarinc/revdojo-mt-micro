<?php

namespace Revdojo\MT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantDomain extends Model
{
    use SoftDeletes;
    protected $connection = 'mysql_tenant_service';

    protected $table = 'tenants_domains';

    protected $fillable = [
        'name',
        'domain',
        'tenant_id',
        'tenant_base_id',
    ];


    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function tenantBase()
    {
        return $this->belongsTo(TenantBase::class);
    }
}
