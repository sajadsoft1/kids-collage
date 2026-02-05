<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use Livewire\Component;
use Morilog\Jalali\Jalalian;

class JalaliDatePicker extends Component
{
    public ?string $value = null; // gregorian Y-m-d

    public function render()
    {
        $jalali = $this->value
            ? Jalalian::fromCarbon(
                \Carbon\Carbon::parse($this->value)
            )->format('Y/m/d')
            : null;

        return view('livewire.admin.shared.jalali-date-picker', [
            'jalali' => $jalali,
        ]);
    }
}
