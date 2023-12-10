<?php

namespace Revdojo\MT\Setups\DatabaseSetup;

use Revdojo\MT\Models\Tenant;
use Illuminate\Support\Facades\Config;

class TenantDatabase
{
    public static function initialize($id)
    {
        $tenant = Tenant::find($id);
        $base = $tenant->tenantBases->first();
        $domain = $tenant->domains->first();

        Config::set('tenancy.tenant_subdomain', $domain->sub_domain);
        Config::set('tenancy.tenant', $tenant);
        Config::set('tenancy.tenant_db', $tenant ? $base->tenancy_db_name : 'base_service');
        
    }

    public static function endInitialize()
    {
        Config::set('tenancy.tenant_subdomain', null);
        Config::set('tenancy.tenant', null);
        Config::set('tenancy.tenant_db', 'base_service');
    }
}

