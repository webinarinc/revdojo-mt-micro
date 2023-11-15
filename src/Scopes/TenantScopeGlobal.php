<?php


namespace Revdojo\MT\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Revdojo\MT\Models\TenantBase;

class TenantScopeGlobal implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $tenant = TenantBase::where('id', config('tenancy.tenant_subdomain'))->first();
        if (config('tenancy.tenant_subdomain') && !$tenant) {
            return abort('403', 'Access denied. Tenant does not exist');
        }

        if (!config('tenancy.tenant')) {
            return;
        }

        $tenantIds = config('tenancy.tenant')->{$model->getTable()}->pluck('id')->toArray();
        $builder->whereIn('id', $tenantIds);
    }
}
