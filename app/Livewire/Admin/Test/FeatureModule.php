<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Test;

use Livewire\Component;

class FeatureModule extends Component
{
    public $module;

    public function mount($module)
    {
        $this->module = $module;
    }

    public function render()
    {
        return view('livewire.admin.test.feature-module');
    }
}
