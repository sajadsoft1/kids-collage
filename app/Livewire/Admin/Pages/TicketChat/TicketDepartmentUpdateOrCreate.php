<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\TicketChat;

use App\Models\User;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Karnoweb\TicketChat\Models\Department;
use Karnoweb\TicketChat\Services\DepartmentService;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class TicketDepartmentUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public Department $model;

    public string $name = '';

    public string $description = '';

    public bool $is_active = true;

    public int $order = 0;

    public string $auto_assign_strategy = 'manual';

    /** @var array<int|string> */
    public array $agent_ids = [];

    public function mount(?Department $ticket_department = null): void
    {
        $this->model = $ticket_department ?? new Department;
        if ($this->model->id) {
            $this->name = $this->model->name;
            $this->description = (string) $this->model->description;
            $this->is_active = $this->model->is_active;
            $this->order = (int) $this->model->order;
            $this->auto_assign_strategy = (string) ($this->model->auto_assign_strategy ?? 'manual');
            $this->agent_ids = $this->model->agents->pluck('id')->map(fn ($id) => (string) $id)->values()->all();
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'is_active' => 'required|boolean',
            'order' => 'required|integer|min:0',
            'auto_assign_strategy' => 'required|in:manual,round_robin,load_balance',
            'agent_ids' => 'array',
            'agent_ids.*' => 'exists:users,id',
        ];
    }

    public function submit(DepartmentService $departmentService): void
    {
        $payload = $this->validate();
        $agentIds = array_map('intval', $payload['agent_ids'] ?? []);
        unset($payload['agent_ids']);

        if ($this->model->id) {
            $this->model->update($payload);
            $this->syncAgents($departmentService, $agentIds);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => __('ticket_chat.department')]),
                redirectTo: route('admin.ticket-chat.departments.index')
            );
        } else {
            $department = Department::query()->create($payload);
            $this->syncAgents($departmentService, $agentIds, $department);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => __('ticket_chat.department')]),
                redirectTo: route('admin.ticket-chat.departments.index')
            );
        }
    }

    /** @param array<int> $agentIds */
    private function syncAgents(DepartmentService $departmentService, array $agentIds, ?Department $department = null): void
    {
        $department ??= $this->model;
        $currentIds = $department->agents->pluck('id')->all();

        foreach (array_diff($currentIds, $agentIds) as $removeId) {
            $departmentService->removeAgent($department, $removeId);
        }
        foreach (array_diff($agentIds, $currentIds) as $addId) {
            $departmentService->addAgent($department, $addId, 'agent');
        }
    }

    /** @return array<int, array{id: string, name: string}> */
    #[Computed]
    public function assignableUsers(): array
    {
        return User::query()
            ->orderBy('name')
            ->orderBy('family')
            ->get(['id', 'name', 'family'])
            ->map(fn (User $u) => [
                'id' => (string) $u->id,
                'name' => trim($u->name . ' ' . ($u->family ?? '')),
            ])
            ->values()
            ->all();
    }

    public function render(): View
    {
        $editMode = (bool) $this->model->id;

        return view('livewire.admin.pages.ticket-chat.ticket-department-update-or-create', [
            'edit_mode' => $editMode,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.ticket-chat.index'), 'label' => __('ticket_chat.title')],
                ['link' => route('admin.ticket-chat.departments.index'), 'label' => __('ticket_chat.departments_manage')],
                ['label' => $editMode ? trans('general.page.edit.title', ['model' => __('ticket_chat.department')]) : trans('general.page.create.title', ['model' => __('ticket_chat.department')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.ticket-chat.departments.index'), 'icon' => 's-arrow-left', 'label' => __('ticket_chat.back_to_list')],
            ],
        ]);
    }
}
