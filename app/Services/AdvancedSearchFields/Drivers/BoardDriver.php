<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Drivers;

use Illuminate\Database\Eloquent\Builder;

class BoardDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'owner_id':
                    $query->whereHas('users', function ($q) use ($item) {
                        $q->where('user_id', $item['from'])
                          ->where('role', 'owner');
                    });
                    break;
                case 'has_cards':
                    if ($item['from'] == 'yes') {
                        $query->has('cards');
                    } else {
                        $query->doesntHave('cards');
                    }
                    break;
                case 'card_count':
                    $operator = $item['operator'] ?? '>=';
                    $query->withCount('cards')
                          ->having('cards_count', $operator, $item['from']);
                    break;
                case 'has_active_cards':
                    if ($item['from'] == 'yes') {
                        $query->whereHas('cards', function ($q) {
                            $q->where('status', 'active');
                        });
                    } else {
                        $query->whereDoesntHave('cards', function ($q) {
                            $q->where('status', 'active');
                        });
                    }
                    break;
            }
        }
        
        return $query;
    }
}
