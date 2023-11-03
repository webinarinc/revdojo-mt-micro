<?php

namespace Revdojo\MT\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class TenantBase extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $connection = 'mysql_tenant_service';
    protected $table = 'tenants_bases';

    public function domains()
    {
        return $this->hasMany(config('tenancy.domain_model'), 'tenant_base_id');
    }
}