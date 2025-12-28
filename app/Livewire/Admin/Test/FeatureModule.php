<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Test;

use Illuminate\View\View;
use Livewire\Component;

class FeatureModule extends Component
{
    public $module;

    public function mount($module): void
    {
        $this->module = $module;
    }

    public function render(): View
    {
        return view('livewire.admin.test.feature-module');
    }
}
