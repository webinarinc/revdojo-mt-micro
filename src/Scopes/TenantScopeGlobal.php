<?php


namespace Revdojo\MT\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScopeGlobal implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (!config('tenancy.tenant')) {
            return;
        }
        
        $tenantIds = config('tenancy.tenant')->{$model->getTable()}->pluck('id')->toArray();
        $builder->whereIn('id', $tenantIds);
    }
}
