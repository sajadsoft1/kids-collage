<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields;

use App\Helpers\StringHelper;
use App\Services\AdvancedSearchFields\Handlers\BaseHandler;
use PowerComponents\LivewirePowerGrid\Facades\Filter;

class AdvancedSearchFieldsService
{
    public static function generate($model): array
    {
        $model = str_replace('\\', '/', $model);
        /** @var BaseHandler $handler */
        $handler = app('App\\Services\\AdvancedSearchFields\\Handlers\\' . StringHelper::convertToClassName(basename($model)) . 'Handler');
        
        return $handler->handle();
    }

    public static function generatePowerGridFilters($model): array
    {
        $data    = self::generate($model);
        $filters = [];
        foreach ($data as $datum) {
            if ($datum['type'] === 'select') {
                $filters[] = Filter::select($datum['key'], $datum['key'])
                    ->dataSource($datum['options'])
                    ->optionLabel('label')
                    ->optionValue('value');
            } elseif ($datum['type'] === 'number') {
                $filters[] = Filter::number($datum['key'], $datum['key']);
            } elseif ($datum['type'] === 'date') {
                $filters[] = Filter::datepicker($datum['key'], $datum['key']);
            } elseif ($datum['type'] === 'input') {
                $filter    = Filter::inputText($datum['key'], $datum['key'])->operators(['contains']);
                $filters[] = $filter;
            }
        }

        return $filters;
    }
}
