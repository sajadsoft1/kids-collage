<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Drivers;

use Illuminate\Database\Eloquent\Builder;

class BannerDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'has_media':
                    if ($item['from'] == 'yes') {
                        $query->has('media');
                    } else {
                        $query->doesntHave('media');
                    }
                    break;
                case 'click_range':
                    $operator = $item['operator'] ?? '>=';
                    $query->where('click', $operator, $item['from']);
                    break;
            }
        }
        
        return $query;
    }
}
