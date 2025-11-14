<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use App\Actions\Exam\StoreExamAction;
use App\Actions\Exam\UpdateExamAction;
use App\Models\Exam;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class ExamUpdateOrCreate extends Component
{
    use Toast;

    public Exam $model;
    public string $title = '';
    public string $description = '';
    public bool $published = false;
    public string $selectedTab = 'basic';
    public array $rules = [
        'groups' => [],
        'group_logic' => 'or',
    ];

    public function mount(Exam $exam): void
    {
        $this->model = $exam;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->description = $this->model->description;
            $this->published = $this->model->published->value;
            $this->rules = $this->model->getRules() ?? [
                'groups' => [],
                'group_logic' => 'or',
            ];
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
        $payload['rules'] = $this->rules;

        if ($this->model->id) {
            UpdateExamAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('exam.model')]),
                redirectTo: route('admin.exam.index')
            );
        } else {
            StoreExamAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('exam.model')]),
                redirectTo: route('admin.exam.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.exam.exam-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.exam.index'), 'label' => trans('general.page.index.title', ['model' => trans('exam.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('exam.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.exam.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
