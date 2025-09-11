<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Drivers;

use Illuminate\Database\Eloquent\Builder;

class CommentDriver extends BaseDriver
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
                case 'is_parent':
                    if ($item['from'] == 'yes') {
                        $query->whereNull('parent_id');
                    } else {
                        $query->whereNotNull('parent_id');
                    }
                    break;
                case 'content_type':
                    $query->where('morphable_type', $item['from']);
                    break;
                case 'has_rating':
                    if ($item['from'] == 'yes') {
                        $query->whereNotNull('rate');
                    } else {
                        $query->whereNull('rate');
                    }
                    break;
            }
        }
        
        return $query;
    }
}
