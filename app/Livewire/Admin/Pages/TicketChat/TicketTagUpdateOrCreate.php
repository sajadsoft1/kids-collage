<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\TicketChat;

use Illuminate\View\View;
use Karnoweb\TicketChat\Models\Tag;
use Livewire\Component;
use Mary\Traits\Toast;

class TicketTagUpdateOrCreate extends Component
{
    use Toast;

    public Tag $model;

    public string $name = '';

    public ?string $color = null;

    public string $description = '';

    public function mount(?Tag $ticket_tag = null): void
    {
        $this->model = $ticket_tag ?? new Tag;
        if ($this->model->id) {
            $this->name = $this->model->name;
            $this->color = $this->model->color;
            $this->description = (string) $this->model->description;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:5000',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            $this->model->update($payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => __('ticket_chat.tag')]),
                redirectTo: route('admin.ticket-chat.tags.index')
            );
        } else {
            Tag::query()->create($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => __('ticket_chat.tag')]),
                redirectTo: route('admin.ticket-chat.tags.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.ticket-chat.ticket-tag-update-or-create', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.ticket-chat.index'), 'label' => __('ticket_chat.title')],
                ['link' => route('admin.ticket-chat.tags.index'), 'label' => __('ticket_chat.manage_tags')],
                ['label' => $this->model->id ? __('general.edit') : __('general.page.create.title', ['model' => __('ticket_chat.tag')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.ticket-chat.tags.index'), 'icon' => 's-arrow-left', 'label' => __('ticket_chat.back_to_list')],
            ],
        ]);
    }
}
