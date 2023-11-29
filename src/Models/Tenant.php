<?php

namespace Revdojo\MT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Revdojo\MT\Models\TenantBase;
use Revdojo\MT\Models\TenantDomain;
use Revdojo\MT\Models\Company;
use Revdojo\MT\Traits\Fillable;

class Tenant extends Model
{
    use SoftDeletes,
        Fillable;
    protected $connection = 'mysql_tenant_service';

    protected $table = 'tenants';

    protected static function boot()
    {
        parent::boot();
        static::bootFillable();
    }

    public function tenantBases()
    {
        return $this->hasMany(TenantBase::class);
    }
    public function domains()
    {
        return $this->hasMany(TenantDomain::class);
    }
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'base_service.tenant_company', 'tenant_id', 'company_id')->withPivot('tenant_id');
    }
}
