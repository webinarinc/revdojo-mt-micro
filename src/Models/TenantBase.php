<?php

namespace Revdojo\MT\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\SoftDeletes;
use Revdojo\MT\Models\TenantDomain;

class TenantBase extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase,
        HasDomains,
        SoftDeletes;

    protected $connection = 'mysql_tenant_service';
    protected $table = 'tenants_bases';

    protected $fillable = [
        'id',
        'tenant_id',
        'data',
    ];

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