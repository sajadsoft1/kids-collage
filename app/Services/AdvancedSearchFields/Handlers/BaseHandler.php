<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Handlers;

abstract class BaseHandler
{
    const INPUT  = 'input';
    const DATE   = 'date';
    const SELECT = 'select';
    const NUMBER = 'number';

    abstract public function handle(): array;

    public function add(string $key, string $label, string $type, array $options = [], ?array $operators=null): array
    {
        return [
            'key'       => $key,
            'label'     => $label,
            'type'      => $type,
            'operators' => $operators ?? $this->loadOperators($type),
        ] + (count($options) ? ['options' => $options] : []);
    }

    public function option(string $value, string $label): array
    {
        return compact('value', 'label');
    }

    public function defaultNumberOperators(): array
    {
        return [
            'مساوی' => '=',
            'مخالف' => '!=',
            '>'     => '>',
            '>='    => '>=',
        ];
    }

    private function defaultInputOperators(): array
    {
        return [
            'شامل' => 'like',
        ];
    }

    private function defaultDateOperators(): array
    {
        return [
            'مساوی' => 'at',
            'بین'   => 'between',
        ];
    }

    private function defaultSelectOperators(): array
    {
        return [
            'مساوی' => '=',
        ];
    }

    private function loadOperators($type): array
    {
        return match ($type) {
            self::NUMBER => $this->defaultNumberOperators(),
            self::INPUT  => $this->defaultInputOperators(),
            self::DATE   => $this->defaultDateOperators(),
            self::SELECT => $this->defaultSelectOperators(),
            default      => [],
        };
    }
}
