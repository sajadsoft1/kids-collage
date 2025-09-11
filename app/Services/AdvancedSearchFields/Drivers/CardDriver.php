<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Drivers;

use App\Enums\CardStatusEnum;
use Illuminate\Database\Eloquent\Builder;

class CardDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'assignee_id':
                    $query->whereHas('assignees', function ($q) use ($item) {
                        $q->where('user_id', $item['from']);
                    });
                    break;
                case 'reviewer_id':
                    $query->whereHas('reviewers', function ($q) use ($item) {
                        $q->where('user_id', $item['from']);
                    });
                    break;
                case 'watcher_id':
                    $query->whereHas('watchers', function ($q) use ($item) {
                        $q->where('user_id', $item['from']);
                    });
                    break;
                case 'overdue':
                    if ($item['from'] == 'yes') {
                        $query->where('due_date', '<', now())
                              ->where('status', '!=', CardStatusEnum::COMPLETED->value);
                    } else {
                        $query->where(function ($q) {
                            $q->where('due_date', '>=', now())
                              ->orWhere('status', CardStatusEnum::COMPLETED->value)
                              ->orWhereNull('due_date');
                        });
                    }
                    break;
                case 'has_assignees':
                    if ($item['from'] == 'yes') {
                        $query->has('assignees');
                    } else {
                        $query->doesntHave('assignees');
                    }
                    break;
            }
        }
        
        return $query;
    }
}
