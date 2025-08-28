<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class CoursePage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.course-page')
            ->layout('components.layouts.web');
    }
}
