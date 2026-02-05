<?php

declare(strict_types=1);

namespace App\Livewire\Support\Fields;

use Livewire\Component;

class JalaliDate extends Component
{
    public ?string $value = null;

    public function render()
    {
        return view('livewire.support.fields.jalali-date');
    }
}
