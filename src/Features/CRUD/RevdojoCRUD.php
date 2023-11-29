<?php

namespace Revdojo\MT\Features\CRUD;

use Revdojo\MT\Helpers\GenerateHelper;

abstract class RevdojoCRUD
{

    public static function create(
        $model, 
        Array $data, 
        $subTable = true
    ) {
        $model->fill($data);
        $model->save();

        if ($subTable) {
            SELF::subTables($model, $data);
        }

        return $model;
    }

    protected static function subTables($model, $data)
    {
        if (!method_exists($model, 'allRelationships')) {
            \Log::error("AllRelationship is not declared to model.");
            return;
        }

        foreach ($model->allRelationships() as $relationship) {
            $relationshipType = $relationship['type'] == 'HasMany' || $relationship['type'] == 'HasOne';
            if ($relationshipType && array_key_exists($relationship['modelRelation'], $data)) {
                $infos = collect($data[$relationship['modelRelation']]);
                SELF::subTablesCreate($model, $relationship, $infos, $relationship['type']);
            }

            if ($relationship['type'] == 'BelongsToMany' && array_key_exists($relationship['foreignPivotKey'], $data)) {
                $model->{$relationship['modelRelation']}()->sync($data[$relationship['foreignPivotKey']], false);
            }
        }
    }

    protected static function subTablesCreate(
        $model, 
        $relationship, 
        $infos, 
        $type
    ) {

        if ($type == 'HasMany') {
            foreach($infos as $info) {
                $model->{$relationship['modelRelation']}()->create($info);
    
                //here
                //add update action here
            }
        }

        if ($type == 'HasOne') {
            $model->{$relationship['modelRelation']}()->create($infos->toArray());
        }
    }

    public static function update($model, $data, $subTable = true)
    {
        $model->fill($data);
        $model->save();


        if ($subTable) {
            SELF::subTables($model, $data);
        }

        return $model;
    }

    /**
     *  $all = true, soft delete the model including all related tables
     */
    public static function destroy($model, $all = false)
    {
        if ($all) {
           SELF::processRelations($model, 'delete');
        }

        return $model->delete();
        
    }

    /**
     *  process Relations will scan model's related tables 
     *  and will do the given actions (soft delete, force delete, restore)
     */
    public static function processRelations($model, $action) 
    {
        if (!method_exists($model, 'allRelationships')) {
            \Log::error("Model as been $action. AllRelationship is not declared to model, unable to $action related table.");
            return;
        }

        foreach ($model->allRelationships() as $relationship) {
            if ($relationship['type'] == 'HasMany') {
                $relatedId = $model->{$relationship['modelRelation']}()->withTrashed()->pluck('id')->toArray();
                $relationship['model']::whereIn('id', $relatedId)->{$action}();
            }

            if ($relationship['type'] == 'BelongsToMany' && $action == 'forceDelete') {
                $model->{$relationship['modelRelation']}()->detach();
            }   
        }
    }

    public static function forceDelete($model, $all = false)
    {
        if ($all) {
            SELF::processRelations($model, 'forceDelete');
        }

       return $model->forceDelete();
    }

    public static function restore($model, $all = false)
    {
        if ($all) {
            SELF::processRelations($model, 'restore');
        }

        $model->restore();

        return $model;
    }
}