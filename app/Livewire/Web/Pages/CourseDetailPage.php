<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Models\CourseTemplate;
use App\Services\SeoBuilder;
use Livewire\Component;

class CourseDetailPage extends Component
{
    public CourseTemplate $course;

    public function render()
    {
        SeoBuilder::create($this->course)
            ->course()
            ->apply();

        return view('livewire.web.pages.course-detail-page')
            ->layout('components.layouts.web');
    }
}
