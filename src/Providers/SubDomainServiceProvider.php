<?php

namespace Revdojo\MT\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Revdojo\MT\Models\TenantDomain;
class SubDomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
       
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {

            if (!config('database.connections.mysql_tenant_service')) {
                return;
            }
            
            $url = request()->root();
            $parsedUrl = parse_url($url);

            if (!isset($parsedUrl['host'])) {
            return;
            }

            preg_match('/^(.*?)\./', $parsedUrl['host'], $matches);
            $subdomain = empty($matches) ? null : $matches[1];
            Config::set('tenancy.tenant_subdomain', $subdomain);

            $domain = TenantDomain::where('sub_domain', $subdomain)->first();
            
            if (!$domain) {
                Config::set('tenancy.tenant_db', 'base_service');
                return;
            }
            
            $tenant = $domain->tenant;
            Config::set('tenancy.tenant', $tenant);
            Config::set('tenancy.tenant_db', $tenant ? $domain->tenantBase->tenancy_db_name : 'base_service');
            
        } catch (\Throwable $th) {
            return;
        }
    }
}
