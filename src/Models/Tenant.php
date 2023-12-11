<?php

namespace Revdojo\MT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Revdojo\MT\Models\TenantBase;
use Revdojo\MT\Models\TenantDomain;
use Revdojo\MT\Models\Company;
use Revdojo\MT\Models\Store;
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
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'base_service.tenant_store', 'tenant_id', 'store_id')->withPivot('tenant_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'base_service.tenant_user', 'tenant_id', 'user_id')->withPivot('tenant_id');
    }
}
