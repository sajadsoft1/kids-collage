<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Drivers;

use Illuminate\Database\Eloquent\Builder;

class UserDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

               $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
               foreach ($extra_filters as $item) {
                switch ($item['column']) {
                    case 'user_type':
                        if ($item['from'] == 'admin') {
                            $query->whereHas('roles');
                        } elseif($item['from'] == 'blogger') {
                            $query->whereHas('blogs');
                        }
                        break;
                }
               }
        return $query;
    }
}
