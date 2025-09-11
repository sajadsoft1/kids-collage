<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Drivers;

use Illuminate\Database\Eloquent\Builder;

class TagDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'usage_count':
                    $operator = $item['operator'] ?? '>=';
                    // Assuming tags have a usage count calculated from relationships
                    $query->withCount(['blogs', 'portfolios'])
                          ->havingRaw('(blogs_count + portfolios_count) ' . $operator . ' ?', [$item['from']]);
                    break;
                case 'is_popular':
                    if ($item['from'] == 'yes') {
                        // Popular tags have usage count > 10 (example threshold)
                        $query->withCount(['blogs', 'portfolios'])
                              ->havingRaw('(blogs_count + portfolios_count) > ?', [10]);
                    } else {
                        $query->withCount(['blogs', 'portfolios'])
                              ->havingRaw('(blogs_count + portfolios_count) <= ?', [10]);
                    }
                    break;
                case 'has_content':
                    if ($item['from'] == 'yes') {
                        $query->where(function ($q) {
                            $q->has('blogs')->orHas('portfolios');
                        });
                    } else {
                        $query->doesntHave('blogs')->doesntHave('portfolios');
                    }
                    break;
            }
        }
        
        return $query;
    }
}
