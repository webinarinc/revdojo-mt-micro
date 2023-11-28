<?php

declare(strict_types=1);

namespace Revdojo\MT\Traits;

use  Revdojo\MT\Scopes\TenantScopeGlobal;
use Revdojo\MT\Helpers\GenerateHelper;

trait TenantLink
{
    protected static function bootTenantLink()
    {
        static::addGlobalScope(new TenantScopeGlobal);
    }
}