<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Drivers;

use Illuminate\Database\Eloquent\Builder;

class CategoryDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'has_children':
                    if ($item['from'] == 'yes') {
                        $query->has('children');
                    } else {
                        $query->doesntHave('children');
                    }
                    break;
                case 'content_count':
                    $operator = $item['operator'] ?? '>=';
                    $query->withCount(['blogs', 'faqs', 'portfolios'])
                          ->havingRaw('(blogs_count + faqs_count + portfolios_count) ' . $operator . ' ?', [$item['from']]);
                    break;
            }
        }
        
        return $query;
    }
}
