<?php

namespace Revdojo\MT\Features\CRUD;

use Revdojo\MT\Helpers\GenerateHelper;

abstract class RevdojoCRUD
{

    public static function create($model, $data)
    {
        $model->fill($data);

        $systemId = GenerateHelper::generateSystemId(null,$model::class);
        $model->system_id = $systemId;
        $model->save();

        // SELF::subTables($model, $data);
        
        return $model;
    }

    protected static function subTables($model, $data)
    {

        // if (!$data->sub_tables) {
        //     return;
        // }

        // dd($model, $data->sub_tables);

        // foreach ($data->sub_tables as $subTable) {
        //     $
        // }

        // dd($sample);
        
    }

    public static function update($model, $data)
    {
        $model->fill($data);
        $model->save();

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