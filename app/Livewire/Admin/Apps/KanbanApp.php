<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Apps;

use App\Enums\CardStatusEnum;
use App\Enums\CardTypeEnum;
use App\Enums\PriorityEnum;
use App\Models\Board;
use App\Models\Card;
use App\Models\CardFlow;
use App\Models\CardHistory;
use App\Models\Column;
use App\Models\User;
use App\Services\LivewireTemplates\KanbanTemplate;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;

/**
 * Class KanbanApp
 *
 * Full-featured Kanban board application with dynamic boards, columns, and cards.
 * Supports card movement with flow validation, user assignments, and history tracking.
 */
class KanbanApp extends KanbanTemplate
{
    public Board $board;
    public ?Card $selectedCard          = null;
    public bool $showBoardModal         = false;
    public bool $showCreateCardModal    = false;
    public bool $showEditCardModal      = false;
    public bool $showCardModal          = false;
    public bool $showColumnModal        = false;
    public bool $showFlowModal          = false;
    public bool $showHistoryModal       = false;

    public array $cardForm = [
        'title'            => '',
        'description'      => '',
        'card_type'        => 'task',
        'priority'         => 'medium',
        'status'           => 'active',
        'due_date'         => '',
        'column_id'        => '',
        'assignees'        => [],
        'reviewers'        => [],
        'watchers'         => [],
        'extra_attributes' => [],
    ];

    public array $columnForm = [
        'name'        => '',
        'description' => '',
        'color'       => '#6B7280',
        'wip_limit'   => null,
        'is_active'   => true,
    ];

    public array $flowForm = [
        'name'           => '',
        'description'    => '',
        'from_column_id' => '',
        'to_column_id'   => '',
        'is_active'      => true,
        'condition_json' => [],
    ];

    public function afterMount($extras = []): void
    {
        parent::afterMount($extras);

        // Enable record clicking and sorting
        $this->sortable                   = true;
        $this->sortableBetweenStatuses    = true;
        $this->recordClickEnabled         = true;
        $this->beforeStatusBoardView      = 'livewire.admin.apps.kanban-header';
        $this->afterStatusBoardView       = 'livewire.admin.apps.kanban-footer';

        // Set board from extras if provided
        if (isset($extras['board'])) {
            $this->board = $extras['board'];
        }
    }

    /** Get the statuses (columns) for the selected board. */
    public function statuses(): Collection
    {
        if ( ! $this->board) {
            return collect();
        }

        return $this->board->columns()
            ->where('is_active', true)
            ->withCount('cards') // Eager load card counts to avoid N+1 queries
            ->orderBy('order')
            ->get()
            ->map(function ($column) {
                return [
                    'id'          => (string) $column->id,
                    'title'       => $column->name,
                    'color'       => $column->color,
                    'wip_limit'   => $column->wip_limit,
                    'wip_count'   => $column->cards_count, // Use the eager loaded count
                    'description' => $column->description,
                ];
            });
    }

    /** Get the records (cards) for the selected board. */
    public function records(): Collection
    {
        if ( ! $this->board) {
            return collect();
        }

        return $this->board->cards()
            ->with(['column', 'assignees', 'reviewers', 'watchers'])
            ->orderBy('order')
            ->get()
            ->map(function ($card) {
                return [
                    'id'               => $card->id,
                    'title'            => $card->title,
                    'status'           => (string) $card->column_id,
                    'description'      => $card->description,
                    'card_type'        => $card->card_type->value,
                    'priority'         => $card->priority->value,
                    'status_enum'      => $card->status->value,
                    'due_date'         => $card->due_date?->format('Y-m-d'),
                    'is_overdue'       => $card->isOverdue(),
                    'days_until_due'   => $card->getDaysUntilDue(),
                    'assignees'        => $card->assignees->pluck('name')->toArray(),
                    'reviewers'        => $card->reviewers->pluck('name')->toArray(),
                    'watchers'         => $card->watchers->pluck('name')->toArray(),
                    'extra_attributes' => $card->extra_attributes,
                ];
            });
    }

    public function onStatusSorted($recordId, $statusId, $orderedIds): void
    {
        $this->updateCardsOrder($orderedIds);
    }

    public function onStatusChanged($recordId, $statusId, $fromOrderedIds, $toOrderedIds): void
    {
        try {
            $user = Auth::user();
            $card = Card::findOrFail($recordId);

            // Check flow rules
            // $this->validateCardFlow($card, $toColumn);

            $fromColumnId = $card->column_id;

            DB::transaction(function () use ($card, $statusId, $fromOrderedIds, $toOrderedIds, $user, $fromColumnId) {
                // Update card column and order
                $card->update([
                    'column_id' => $statusId,
                ]);

                // Reorder cards in the source column
                $this->updateCardsOrder($fromOrderedIds);
                $this->updateCardsOrder($toOrderedIds);

                // Create history record
                CardHistory::create([
                    'card_id'          => $card->id,
                    'user_id'          => $user->id,
                    'column_id'        => $statusId,
                    'action'           => 'moved',
                    'description'      => "Card moved from {$card->column->name} to {$statusId}",
                    'extra_attributes' => [
                        'from_column_id' => $fromColumnId,
                        'to_column_id'   => $statusId,
                        'moved_at'       => now()->toISOString(),
                    ],
                ]);
            });

            $this->dispatch('card-moved', message: __('kanban.messages.card_moved'));
        } catch (Exception $e) {
            Log::error('Failed to move card', [
                'record_id' => $recordId,
                'status_id' => $statusId,
                'error'     => $e->getMessage(),
                'user_id'   => Auth::id(),
            ]);

            $this->dispatch('card-move-failed', message: __('kanban.messages.card_move_failed'));
        }
    }

    public function onRecordClick($recordId): void
    {
        $this->selectedCard  = Card::with(['column', 'assignees', 'reviewers', 'watchers'])->find($recordId);
        $this->showCardModal = true;
    }

    public function styles(): array
    {
        $baseStyles                  = parent::styles();
        $baseStyles['wrapper']       = 'w-full flex flex-1 space-x-4 overflow-x-auto py-5 px-2';
        $baseStyles['statusWrapper'] = 'min-w-[320px] max-w-full flex-1 shadow-lg h-auto gap-y-2';
        $baseStyles['status']        = 'rounded flex flex-col flex-1 gap-2';
        $baseStyles['record']        = 'shadow-sm bg-white p-2 rounded border text-sm text-gray-800';
        $baseStyles['statusRecords'] = 'space-y-2 px-1 pt-2 pb-2';
        $baseStyles['ghost']         = 'bg-gray-400';

        return $baseStyles;
    }

    private function updateCardsOrder(array $orderedIds): void
    {
        try {
            collect($orderedIds)
                ->each(function ($id, $index) {
                    $card = Card::find($id);
                    if ($card) {
                        $card->update(['order' => $index]);
                    }
                });
        } catch (Exception $e) {
            Log::error('Failed to update cards order', [
                'ordered_ids' => $orderedIds,
                'error'       => $e->getMessage(),
                'user_id'     => Auth::id(),
            ]);

            throw $e;
        }
    }

    /** Validate card flow rules before allowing movement. */
    private function validateCardFlow(Card $card, Column $toColumn): void
    {
        $flows = CardFlow::where('board_id', $card->board_id)
            ->where('from_column_id', $card->column_id)
            ->where('to_column_id', $toColumn->id)
            ->where('is_active', true)
            ->get();

        if ($flows->isEmpty()) {
            // No flow rules defined, allow movement
            return;
        }

        // Check if any flow rule allows the movement
        $allowed = false;
        foreach ($flows as $flow) {
            if ($flow->checkConditions($card)) {
                $allowed = true;

                break;
            }
        }

        if ( ! $allowed) {
            throw new ValidationException(__('kanban.messages.flow_rule_violated'));
        }
    }

    /** Create a new card. */
    public function createCard(): void
    {
        try {
            $this->validate([
                'cardForm.title'       => 'required|string|max:255',
                'cardForm.description' => 'nullable|string',
                'cardForm.card_type'   => 'required|string|in:' . implode(',', CardTypeEnum::values()),
                'cardForm.priority'    => 'required|string|in:' . implode(',', PriorityEnum::values()),
                'cardForm.status'      => 'required|string|in:' . implode(',', CardStatusEnum::values()),
                'cardForm.due_date'    => 'nullable|date',
                'cardForm.column_id'   => 'required|exists:columns,id',
            ]);

            $user = Auth::user();

            DB::transaction(function () use ($user) {
                $card = Card::create([
                    'board_id'         => $this->board->id,
                    'column_id'        => $this->cardForm['column_id'],
                    'title'            => $this->cardForm['title'],
                    'description'      => $this->cardForm['description'],
                    'card_type'        => $this->cardForm['card_type'],
                    'priority'         => $this->cardForm['priority'],
                    'status'           => $this->cardForm['status'],
                    'due_date'         => $this->cardForm['due_date'],
                    'order'            => $this->getNextCardOrder($this->cardForm['column_id']),
                    'extra_attributes' => $this->cardForm['extra_attributes'],
                ]);

                // Assign users
                $this->assignUsersToCard($card);

                // Create history record
                CardHistory::create([
                    'card_id'          => $card->id,
                    'user_id'          => $user->id,
                    'column_id'        => $card->column_id,
                    'action'           => 'created',
                    'description'      => 'Card created',
                    'extra_attributes' => [
                        'created_by' => $user->id,
                        'created_at' => now()->toISOString(),
                    ],
                ]);
            });

            $this->resetCardForm();
            $this->showCreateCardModal = false;

            $this->dispatch('card-created', message: __('kanban.messages.card_created'));
        } catch (ValidationException $e) {
            // Re-throw validation exceptions to show field errors
            throw $e;
        } catch (Exception $e) {
            Log::error('Failed to create card', [
                'card_form' => $this->cardForm,
                'error'     => $e->getMessage(),
                'user_id'   => Auth::id(),
            ]);

            $this->dispatch('card-creation-failed', message: __('kanban.messages.card_creation_failed'));
        }
    }

    /** Get the next order number for a card in a column. */
    private function getNextCardOrder(int|string $columnId): int
    {
        return Card::where('column_id', (int) $columnId)->count();
    }

    /** Assign users to a card based on roles. */
    private function assignUsersToCard(Card $card): void
    {
        $assignments = [
            'assignees' => 'assignee',
            'reviewers' => 'reviewer',
            'watchers'  => 'watcher',
        ];

        foreach ($assignments as $formKey => $role) {
            if ( ! empty($this->cardForm[$formKey])) {
                $card->users()->attach($this->cardForm[$formKey], ['role' => $role]);
            }
        }
    }

    /** Reset card form. */
    private function resetCardForm(): void
    {
        $this->cardForm = [
            'title'            => '',
            'description'      => '',
            'card_type'        => 'task',
            'priority'         => 'medium',
            'status'           => 'active',
            'due_date'         => '',
            'column_id'        => '',
            'assignees'        => [],
            'reviewers'        => [],
            'watchers'         => [],
            'extra_attributes' => [],
        ];
    }

    /** Get available users for assignment. */
    public function getAvailableUsers(): Collection
    {
        return User::where('status', 1)->get();
    }

    /** Get card type options. */
    public function getCardTypeOptions(): array
    {
        return CardTypeEnum::options();
    }

    /** Get priority options. */
    public function getPriorityOptions(): array
    {
        return PriorityEnum::options();
    }

    /** Get status options. */
    public function getStatusOptions(): array
    {
        return CardStatusEnum::options();
    }

    /** Switch from card details modal to edit modal. */
    public function switchToEditModal(): void
    {
        $this->showEditCardModal = true;
        $this->showCardModal     = false;
    }
}
