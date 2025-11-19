<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\FlashCard;

use App\Actions\FlashCard\StoreFlashCardAction;
use App\Actions\FlashCard\UpdateFlashCardAction;
use App\Models\FlashCard;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class FlashCardUpdateOrCreate extends Component
{
    use Toast;

    public FlashCard $model;
    public string $title = '';
    public string $front = '';
    public string $back = '';
    public bool $favorite = false;

    public function mount(FlashCard $flashCard): void
    {
        $this->model = $flashCard;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->back = $this->model->back;
            $this->front = $this->model->front;
            $this->favorite = $this->model->favorite->asBoolean();
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string',
            'front' => 'nullable|string',
            'back' => 'nullable|string',
            'favorite' => 'required',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateFlashCardAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('flashCard.model')]),
                redirectTo: route('admin.flash-card.index')
            );
        } else {
            StoreFlashCardAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('flashCard.model')]),
                redirectTo: route('admin.flash-card.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.flashCard.flashCard-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.flash-card.index'), 'label' => trans('general.page.index.title', ['model' => trans('flashCard.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('flashCard.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.flash-card.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
