<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Drivers;

use Illuminate\Database\Eloquent\Builder;

class SliderDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        //        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        //        foreach ($extra_filters as $item) {
        //
        //        }
        return $query;
    }
}
