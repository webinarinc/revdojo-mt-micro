<?php

declare(strict_types=1);

namespace Revdojo\MT\Traits;

use Illuminate\Support\Facades\Schema;
use Revdojo\MT\Helpers\GenerateHelper;
use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;

trait Fillable
{
  
    protected static function bootFillable()
    {
        static::creating(function ($model) {
            if (!$model->system_id) {
                $model->system_id = GenerateHelper::generateSystemId(null, $model::class);
            }
        });

        static::saving(function ($model) {
            if (!$model->system_id) {
                $model->system_id = GenerateHelper::generateSystemId(null, $model::class);
            }
        });
    }

    public function getFillable()
    {
        return Schema::connection($this->getConnectionName())->getColumnListing($this->getTable());
    }

    public function allRelationships() 
    {
        $model = new static;

        $relationships = [];

        foreach((new ReflectionClass($model))->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
        {
            if ($method->class != get_class($model) ||
                !empty($method->getParameters()) ||
                $method->getName() == __FUNCTION__) {
                continue;
            }

            try {
                $return = $method->invoke($model);
                if ($return instanceof Relation) {
                    $foreignPivotKey = null;
                    if ((new ReflectionClass($return))->getShortName() == 'BelongsToMany') {
                        $foreign = (new ReflectionClass($return))->getMethod('getForeignPivotKeyName');
                        $foreign->setAccessible(true);

                        $foreignPivotKey = $foreign->invoke($return);
                    }

                    $relationships[$method->getName()] = [
                        'type' => (new ReflectionClass($return))->getShortName(),
                        'model' => (new ReflectionClass($return->getRelated()))->getName(),
                        'modelRelation' => $method->getName(),
                        'foreignPivotKey' => $foreignPivotKey,
                    ];
                }
            } catch(ErrorException $e) {}
        }

        return $relationships;
    }

}