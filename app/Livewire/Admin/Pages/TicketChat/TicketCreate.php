<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\TicketChat;

use App\Traits\CrudHelperTrait;
use Karnoweb\TicketChat\Facades\Ticket;
use Karnoweb\TicketChat\Models\Department;
use Karnoweb\TicketChat\Services\AttachmentService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class TicketCreate extends Component
{
    use CrudHelperTrait;
    use WithFileUploads;

    #[Validate('required|min:5|max:255')]
    public string $title = '';

    #[Validate('required|min:10|max:5000')]
    public string $description = '';

    #[Validate('nullable|exists:tc_departments,id')]
    public ?int $department_id = null;

    #[Validate('in:low,medium,high,urgent')]
    public string $priority = 'medium';

    /** @var array<int, \Illuminate\Http\UploadedFile> */
    #[Validate(['files.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip'])]
    public array $files = [];

    public bool $isSubmitting = false;

    public function mount(): void
    {
        $first = Department::query()->where('is_active', true)->orderBy('order')->orderBy('name')->first();
        if ($first) {
            $this->department_id = $first->id;
        }
    }

    public function removeFile(int $index): void
    {
        array_splice($this->files, $index, 1);
    }

    public function submit(AttachmentService $attachmentService): mixed
    {
        $this->validate();
        $this->isSubmitting = true;

        try {
            $ticket = Ticket::create([
                'title' => $this->title,
                'created_by' => (int) auth()->id(),
                'department_id' => $this->department_id,
                'priority' => $this->priority,
            ]);

            $message = Ticket::reply($ticket, (int) auth()->id(), $this->description);

            foreach ($this->files as $file) {
                $attachmentService->upload($message, $file);
            }

            $this->reset(['title', 'description', 'files']);
            session()->flash('success', __('ticket_chat.ticket_created', ['number' => $ticket->ticket_number]));

            return $this->redirect(route('admin.ticket-chat.show', ['ticket' => $ticket->id]), navigate: true);
        } catch (Throwable $e) {
            $this->addError('general', $e->getMessage());

            return null;
        } finally {
            $this->isSubmitting = false;
        }
    }

    #[Computed]
    public function departments()
    {
        return Department::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function render()
    {
        return view('livewire.admin.pages.ticket-chat.ticket-create', [
            'departments' => $this->departments,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.ticket-chat.index'), 'label' => __('ticket_chat.title')],
                ['label' => __('ticket_chat.create_ticket')],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.ticket-chat.index'), 'icon' => 's-arrow-left', 'label' => __('ticket_chat.back_to_list')],
            ],
        ]);
    }
}
