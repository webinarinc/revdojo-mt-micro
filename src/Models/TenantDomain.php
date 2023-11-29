<?php

namespace Revdojo\MT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Revdojo\MT\Traits\Fillable;

class TenantDomain extends Model
{
    use SoftDeletes,
        Fillable;
        
    protected $connection = 'mysql_tenant_service';

    protected $table = 'tenant_domains';

    protected static function boot()
    {
        parent::boot();
        static::bootFillable();
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function tenantBase()
    {
        return $this->belongsTo(TenantBase::class);
    }
}
