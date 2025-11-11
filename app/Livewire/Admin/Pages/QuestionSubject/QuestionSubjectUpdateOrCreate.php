<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionSubject;

use App\Actions\QuestionSubject\StoreQuestionSubjectAction;
use App\Actions\QuestionSubject\UpdateQuestionSubjectAction;
use App\Models\QuestionSubject;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class QuestionSubjectUpdateOrCreate extends Component
{
    use Toast;

    public QuestionSubject $model;
    public string $title = '';
    public string $description = '';
    public bool $published = false;

    public function mount(QuestionSubject $questionSubject): void
    {
        $this->model = $questionSubject;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->description = $this->model->description;
            $this->published = $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'published' => 'required',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateQuestionSubjectAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('questionSubject.model')]),
                redirectTo: route('admin.questionSubject.index')
            );
        } else {
            StoreQuestionSubjectAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('questionSubject.model')]),
                redirectTo: route('admin.questionSubject.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.questionSubject.questionSubject-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.question-subject.index'), 'label' => trans('general.page.index.title', ['model' => trans('questionSubject.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('questionSubject.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.question-subject.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
