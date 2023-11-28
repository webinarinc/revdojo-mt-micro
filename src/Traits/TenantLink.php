<?php

declare(strict_types=1);

namespace Revdojo\MT\Traits;

use  Revdojo\MT\Scopes\TenantScopeGlobal;
use Revdojo\MT\Helpers\GenerateHelper;

trait TenantLink
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new TenantScopeGlobal);

        static::creating(function ($model) {
            if (!$model->system_id) {
                $model->system_id = GenerateHelper::generateSystemId(null, $model::class);
            }
        });
    }
}