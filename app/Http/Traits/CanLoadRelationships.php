<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use \Illuminate\Database\Eloquent\Builder;

trait CanLoadRelationships{

    public function loadRelationships(Model|QueryBuilder|Builder $for, ?array $relations = null){
        $relations = $relations ?? $this->relations ?? [];
        foreach ($relations as $relation){
            $for->when($this->shouldIncludeRelations($relation), fn($q)=>$for instanceof Model ? $for->load([$relation]) : $q->with($relation));
        }
        return $for;
    }

    protected function shouldIncludeRelations(string $relation): bool{
        $include = request()->query('include');
        if(!$include){
            return false;
        }
        $relations = array_map('trim',explode(',',$include));
        return in_array($relation,$relations);
    }
}
