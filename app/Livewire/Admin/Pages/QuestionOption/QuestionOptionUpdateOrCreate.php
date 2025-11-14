<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionOption;

use App\Actions\QuestionOption\StoreQuestionOptionAction;
use App\Actions\QuestionOption\UpdateQuestionOptionAction;
use App\Models\QuestionOption;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class QuestionOptionUpdateOrCreate extends Component
{
    use Toast;

    public QuestionOption $model;
    public string $title = '';
    public string $description = '';
    public bool $published = false;

    public function mount(QuestionOption $questionOption): void
    {
        $this->model = $questionOption;
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
            UpdateQuestionOptionAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('questionOption.model')]),
                redirectTo: route('admin.questionOption.index')
            );
        } else {
            StoreQuestionOptionAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('questionOption.model')]),
                redirectTo: route('admin.questionOption.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.questionOption.questionOption-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.question-option.index'), 'label' => trans('general.page.index.title', ['model' => trans('questionOption.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('questionOption.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.question-option.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
