<?php

declare(strict_types=1);

namespace Revdojo\MT\Traits;

use Illuminate\Support\Facades\Schema;
use Revdojo\MT\Helpers\GenerateHelper;

trait Fillable
{
  
    protected static function bootFillable()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = GenerateHelper::generateSystemId(null, $model::class);
            }
        });

        static::saving(function ($model) {
            if (!$model->id) {
                $model->id = GenerateHelper::generateSystemId(null, $model::class);
            }
        });
    }

    public function getFillable()
    {
        return Schema::connection($this->getConnectionName())->getColumnListing($this->getTable());
    }

}