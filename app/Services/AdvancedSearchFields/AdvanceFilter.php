<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields;

use App\Helpers\StringHelper;
use App\Services\AdvancedSearchFields\Drivers\BaseDriver;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class AdvanceFilter implements Filter
{
    public function __construct() {}

    public function __invoke(Builder $query, $value, string $property): void
    {
        $normalizedPath = str_replace('\\', '/', $query->getModel()::class);
        /** @var BaseDriver $handler */
        $handler = app('App\\Services\\AdvancedSearchFields\\Drivers\\' . StringHelper::convertToClassName(basename($normalizedPath)) . 'Driver');
        $handler->handle($query, $value);
    }
}
