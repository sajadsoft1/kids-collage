<?php

declare(strict_types=1);

namespace App\Lib;

use PowerComponents\LivewirePowerGrid\Themes\Theme;

class DaisyUI extends Theme
{
    public string $name = 'daisyui';

    public function table(): array
    {
        return [
            'layout' => [
                'base' => 'align-middle inline-block min-w-full w-full !mt-0 !pt-0',
                'div' => 'relative bg-base-100',
                'table' => 'table table-sm !bg-base-100',
                'container' => 'overflow-x-auto sm:-mx-3',
                'actions' => 'gap-2 !bg-red-500',
            ],

            'header' => [
                'thead' => 'text-base-content !capitalize',
                'tr' => 'bg-base-200 ',
                'th' => '!py-4 text-gray-500 font-bold last:!w-0',
                'thAction' => '',
            ],

            'body' => [
                'tbody' => '',
                'tbodyEmpty' => '',
                'tr' => 'hover:bg-base-300',
                'td' => '',
                'tdEmpty' => '',
                'tdSummarize' => '',
                'trSummarize' => '',
                'tdFilters' => '',
                'trFilters' => '',
                'tdActionsContainer' => 'flex gap-2',
            ],
        ];
    }

    public function layout(): array
    {
        return [
            'table' => $this->root() . '.table-base',
            'header' => $this->root() . '.header',
            'pagination' => $this->root() . '.pagination',
            'footer' => $this->root() . '.footer',
        ];
    }

    public function footer(): array
    {
        return [
            'view' => $this->root() . '.footer',
            'select' => 'select flex rounded-md py-1.5 px-4 pr-7 w-auto',
            'footer' => 'rounded-b-lg !text-base-content bg-base-200',
            'footer_with_pagination' => 'md:flex md:flex-row w-full items-center py-3 overflow-y-auto pl-2 pr-2 relative !text-base-content',
        ];
    }

    public function cols(): array
    {
        return [
            'div' => 'select-none flex items-center gap-1  font-bold text-md',
        ];
    }

    public function editable(): array
    {
        return [
            'view' => $this->root() . '.editable',
            'input' => 'input input-sm',
        ];
    }

    public function toggleable(): array
    {
        return [
            'view' => $this->root() . '.toggleable',
        ];
    }

    public function checkbox(): array
    {
        return [
            'th' => 'px-6 py-3 text-start text-xs font-medium tracking-wider',
            'base' => '',
            'label' => 'flex items-center space-x-3',
            'input' => 'checkbox checkbox-sm',
        ];
    }

    public function radio(): array
    {
        return [
            'th' => 'px-6 py-3 text-start text-xs font-medium tracking-wider',
            'base' => '',
            'label' => 'flex items-center space-x-3',
            'input' => 'radio',
        ];
    }

    public function filterBoolean(): array
    {
        return [
            'view' => $this->root() . '.filters.boolean',
            'base' => 'min-w-[5rem]',
            'select' => 'select lg:min-w-[8rem] md:min-w-[5rem] !w-full',
        ];
    }

    public function filterDatePicker(): array
    {
        return [
            'base' => '',
            'view' => $this->root() . '.filters.date-picker',
            'input' => 'flatpickr flatpickr-input input',
        ];
    }

    public function filterMultiSelect(): array
    {
        return [
            'view' => $this->root() . '.filters.multi-select',
            'base' => 'inline-block relative w-full',
            'select' => 'mt-1',
        ];
    }

    public function filterNumber(): array
    {
        return [
            'view' => $this->root() . '.filters.number',
            'input' => 'w-full min-w-[5rem] block input',
        ];
    }

    public function filterSelect(): array
    {
        return [
            'view' => $this->root() . '.filters.select',
            'base' => '',
            'select' => 'select lg:min-w-[8rem] min-w-[5rem] !w-full',
        ];
    }

    public function filterInputText(): array
    {
        return [
            'view' => $this->root() . '.filters.input-text',
            'base' => 'min-w-[9.5rem]',
            'select' => 'select',
            'input' => 'input',
        ];
    }

    public function searchBox(): array
    {
        return [
            'input' => 'grow',
            'iconClose' => 'text-base-content',
            'iconSearch' => 'text-base-content grow me-2 w-5 h-5',
        ];
    }
}
