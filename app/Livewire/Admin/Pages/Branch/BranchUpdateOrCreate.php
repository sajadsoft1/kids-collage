<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Branch;

use App\Actions\Branch\StoreBranchAction;
use App\Actions\Branch\UpdateBranchAction;
use App\Models\Branch;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class BranchUpdateOrCreate extends Component
{
    use Toast;

    public Branch $model;
    public string $title = '';
    public string $description = '';
    public bool $published = false;

    public function mount(Branch $branch): void
    {
        $this->model = $branch;
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
            UpdateBranchAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('branch.model')]),
                redirectTo: route('admin.branch.index')
            );
        } else {
            StoreBranchAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('branch.model')]),
                redirectTo: route('admin.branch.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.branch.branch-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.branch.index'), 'label' => trans('general.page.index.title', ['model' => trans('branch.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('branch.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.branch.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
