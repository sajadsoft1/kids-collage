<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class CourseDetailPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.course-detail-page')
            ->layout('components.layouts.web');
    }
}
