<?php

declare(strict_types=1);

namespace Revdojo\MT\Traits;

use  Revdojo\MT\Scopes\TenantScopeGlobal;

trait TenantLink
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new TenantScopeGlobal);
    }
}