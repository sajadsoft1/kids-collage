<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MatronicHeader extends Component
{
    #[Locked]
    public bool $showMenu = false;

    /** Mount the component. */
    public function mount(bool $showMenu = false): void
    {
        $this->showMenu = $showMenu;
    }

    public function render(): View
    {
        return view('livewire.admin.shared.matronic-header');
    }
}
