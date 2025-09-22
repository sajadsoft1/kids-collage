<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Filters\Filter;

class DateFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        switch ($value) {
            case 'today':
                $query->whereDate('created_at', now());
                
                break;
            case 'this_week':
                $query->whereBetween(DB::raw('DATE(created_at)'), [now()->startOfWeek(), now()->endOfWeek()]);

                return;
            case 'this_month':
                $query->whereBetween(DB::raw('DATE(created_at)'), [now()->startOfMonth(), now()->endOfMonth()]);

                return;
            case 'this_year':
                $query->whereBetween(DB::raw('DATE(created_at)'), [now()->startOfYear(), now()->endOfYear()]);

                return;
            default:
        }
    }
}
