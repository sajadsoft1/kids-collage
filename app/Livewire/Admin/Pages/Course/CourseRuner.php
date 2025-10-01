<?php

namespace App\Livewire\Admin\Pages\Course;

use App\Models\CourseTemplate;
use Livewire\Component;

class CourseRuner extends Component
{
    public CourseTemplate $courseTemplate;

    public function render()
    {
        return view('livewire.admin.pages.course.course-runer');
    }
}
