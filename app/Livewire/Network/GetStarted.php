<?php

declare(strict_types=1);

namespace App\Livewire\Network;

use Livewire\Component;

class GetStarted extends Component
{
    public function render()
    {
        return view('livewire.network.get-started')
            ->layout('components.layouts.metronic-4');
    }
}
