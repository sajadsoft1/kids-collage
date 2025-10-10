<?php

declare(strict_types=1);

namespace App\Filters;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Filters\Filter;

class FuzzyFilter implements Filter
{
    private array $params;

    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    /*
    |--------------------------------------------------------------------------
    | value: The value that user has entered the search box
    | property: search
    | params: The fields that we want to search in
    |--------------------------------------------------------------------------
    */
    public function __invoke(Builder $query, $value, string $property)
    {
        if (empty($value)) {
            return $query;
        }

        $value = StringHelper::enNum($value);
        $value = mb_strtolower($value, 'UTF-8');

        $query->where(function ($q) use ($value) {
            $firstCheck = false;
            foreach ($this->params as $relation => $fields) {
                if (is_array($fields)) {
                    $this->applyRelationFilter($q, $relation, $fields, $value, $firstCheck);
                } else {
                    $this->applyFieldFilter($q, $fields, $value, $firstCheck);
                }
                $firstCheck = true;
            }
        });
    }

    // Apply filter on relation
    private function applyRelationFilter(Builder $query, string $relation, array $fields, string $value, bool $firstCheck): void
    {
        $method = $firstCheck ? 'orWhereHas' : 'whereHas';
        $query->$method($relation, function ($q) use ($fields, $value) {
            foreach ($fields as $index => $field) {
                $this->applyFieldCondition($q, $field, $value, $index === 0);
            }
        });
    }

    // Apply filter on field
    private function applyFieldFilter(Builder $query, string $field, string $value, bool $firstCheck): void
    {
        $method = $firstCheck ? 'orWhere' : 'where';
        $query->$method(function (Builder $q) use ($field, $value) {
            $this->applyFieldCondition($q, $field, $value, true);
        });
    }

    // Query filter on field
    private function applyFieldCondition(Builder $query, string $field, string $value, bool $isFirst): void
    {
        $table = $query->getModel()->getTable();
        if ($table === 'translations') {
            $this->applyTranslationsCondition($query, $field, $value, $isFirst);
        } else {
            $method = $isFirst ? 'where' : 'orWhere';
            $query->$method(DB::raw('LOWER(' . $table . '.' . $field . ')'), 'LIKE', '%' . $value . '%');
        }
    }

    // Query filter on translations
    private function applyTranslationsCondition(Builder $query, string $field, string $value, bool $isFirst): void
    {
        if ($isFirst) {
            $query->where('translations.key', $field)
                ->where(DB::raw('LOWER(translations.value)'), 'LIKE', '%' . $value . '%');
        } else {
            $query->orWhere(function ($q) use ($field, $value) {
                $q->where('translations.key', $field)
                    ->where(DB::raw('LOWER(translations.value)'), 'LIKE', '%' . $value . '%');
            });
        }
    }
}
