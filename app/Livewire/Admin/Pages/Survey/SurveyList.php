<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Survey;

use App\Enums\ExamTypeEnum;
use App\Models\Exam;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class SurveyList extends Component
{
    use Toast, WithPagination;

    public function edit(?int $examId = null): void
    {
        $this->redirect(route('admin.survey.edit', [
            'exam' => $examId,
        ]), true);
    }

    public function results($examId): void
    {
        $this->redirect(route('admin.survey.results', [
            'exam' => $examId,
        ]), true);
    }

    public function render(): View
    {
        return view(
            'livewire.admin.pages.survey.survey-list',
            data: [
                'surveys'            => Exam::where('type', ExamTypeEnum::SURVEY)
                    ->orderByDesc('created_at')
                    ->paginate(15),
                'breadcrumbs'        => [
                    ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                    ['label' => trans('_menu.survey_management')],
                ],
                'breadcrumbsActions' => [
                    ['link' => route('admin.survey.create'), 'icon' => 's-plus', 'label' => 'ایجاد نظر سنجی جدید'],
                ],
            ]
        );
    }
}
