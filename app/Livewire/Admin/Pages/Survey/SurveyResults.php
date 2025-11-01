<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Survey;

use App\Models\Exam;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class SurveyResults extends Component
{
    use Toast;

    public Exam $exam;

    public function mount(Exam $exam): void
    {
        $this->exam = $exam;
    }

    public function render(): View
    {
        return view('livewire.admin.pages.survey.survey-results', [
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.survey.index'), 'label' => trans('_menu.survey_management')],
                ['label' => 'نتایج نظر سنجی'],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.survey.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
