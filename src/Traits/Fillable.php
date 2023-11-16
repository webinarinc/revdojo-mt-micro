<?php

declare(strict_types=1);

namespace Revdojo\MT\Traits;

use Illuminate\Support\Facades\Schema;

trait Fillable
{
    public function getFillable()
    {
        return Schema::getColumnListing($this->getTable());
    }
}