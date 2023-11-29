<?php

namespace Revdojo\MT\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\SoftDeletes;
use Revdojo\MT\Models\TenantDomain;
use Revdojo\MT\Traits\Fillable;

class TenantBase extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase,
        HasDomains,
        SoftDeletes,
        Fillable;

    protected $connection = 'mysql_tenant_service';
    protected $table = 'tenant_bases';

    protected static function boot()
    {
        parent::boot();
        static::bootFillable();
    }
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function domains()
    {
        return $this->hasMany(TenantDomain::class, 'tenant_base_id');
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'tenant_id',
        ];
    }
}