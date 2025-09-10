<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Drivers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

abstract class BaseDriver
{
    protected array $fillable_columns  = [];
    protected string $table            = '';
    
    abstract public function handle(Builder $query, array $values): Builder;
    
    public function filter(Builder $query, array $values): Builder
    {
        $this->table            = $query->getModel()->getTable() . '.';
        $this->fillable_columns = array_merge($query->getModel()->getFillable(), ['id', 'created_at']);
        foreach ($values as $item) {
            if ( ! in_array($item['column'], $this->fillable_columns, true)) {
                continue;
            }
            $this->addQuery($query, $item, $this->table);
        }
        
        return $query;
    }
    
    /** @param array{contain:bool,column:string,operator:string,from:string,to:string} $item $item */
    public function addQuery(Builder $query, array $item, $table = null): void
    {
        if (Arr::get($item, 'contain', 1)) {
            if ($item['operator'] === 'between') {
                $query->whereBetween($table . $item['column'], [$item['from'], $item['to']]);
            } elseif ($item['operator'] === 'like') {
                $query->where($table . $item['column'], $item['operator'], '%' . $item['from'] . '%');
            } elseif ($item['operator'] === 'in') {
                $query->whereIn($table . $item['column'], $item['from']);
            } elseif ($item['operator'] === 'at') {
                $query->whereDate($table . $item['column'], '=', $item['from']);
            } else {
                $query->where($table . $item['column'], $item['operator'], $item['from']);
            }
        } else {
            if ($item['operator'] === 'between') {
                $query->whereNotBetween($table . $item['column'], [$item['from'], $item['to']]);
            } elseif ($item['operator'] === 'like') {
                $query->whereNot($table . $item['column'], $item['operator'], '%' . $item['from'] . '%');
            } else {
                $query->whereNot($table . $item['column'], $item['operator'], $item['from']);
            }
        }
    }
}
