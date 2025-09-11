<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Drivers;

use Illuminate\Database\Eloquent\Builder;

class TicketDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'has_messages':
                    if ($item['from'] == 'yes') {
                        $query->has('messages');
                    } else {
                        $query->doesntHave('messages');
                    }
                    break;
                case 'message_count':
                    $operator = $item['operator'] ?? '>=';
                    $query->withCount('messages')
                          ->having('messages_count', $operator, $item['from']);
                    break;
                case 'is_closed':
                    if ($item['from'] == 'yes') {
                        $query->whereNotNull('closed_by');
                    } else {
                        $query->whereNull('closed_by');
                    }
                    break;
            }
        }
        
        return $query;
    }
}
