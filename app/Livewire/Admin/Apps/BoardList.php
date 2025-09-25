<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Apps;

use App\Enums\CardStatusEnum;
use App\Models\Board;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

/**
 * Class BoardList
 *
 * Component for listing and managing Kanban boards.
 * Allows users to view, create, edit, and delete boards.
 */
class BoardList extends Component
{
    use WithPagination;

    public bool $showBoardModal  = false;
    public bool $showDeleteModal = false;
    public ?Board $selectedBoard = null;
    public ?Board $boardToDelete = null;

    // Form data
    public array $boardForm = [
        'name'        => '',
        'description' => '',
        'color'       => '#3B82F6',
        'is_active'   => true,
    ];

    // Search and filters
    public string $search = '';
    public string $filter = 'all'; // all, active, inactive

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'all'],
    ];

    protected $listeners = [
        'board-created' => '$refresh',
        'board-updated' => '$refresh',
        'board-deleted' => '$refresh',
    ];

    public function mount(): void
    {
        // Check if user has any boards, if not, show create modal.
        if (Auth::user()?->boards()->count() === 0) {
            $this->showBoardModal = true;
        }
    }

    /** Get boards for the current user with search and filters. */
    public function getBoardsProperty()
    {
        $query = Auth::user()?->boards()
                     ->with(['users', 'columns', 'cards'])
                     ->withCount(['columns', 'cards as completed_cards' => function ($q) {
                         $q->where('status', CardStatusEnum::COMPLETED->value);
                     },'cards as pending_cards' => function ($q) {
                         $q->whereNot('status', CardStatusEnum::COMPLETED->value);
                     }]);

        // Apply search filter
        if ( ! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->filter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->filter === 'inactive') {
            $query->where('is_active', false);
        }

        return $query->orderBy('created_at', 'desc')->paginate(12);
    }

    /** Create a new board.
     * @throws Throwable
     */
    public function createBoard(): void
    {
        $this->validate([
            'boardForm.name'        => 'required|string|max:255',
            'boardForm.description' => 'nullable|string',
            'boardForm.color'       => 'required|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($user) {
            $board = Board::create($this->boardForm);

            // Add the creator as owner
            $board->users()->attach($user->id, ['role' => 'owner']);

            // Create default columns
            $this->createDefaultColumns($board);
        });

        $this->resetBoardForm();
        $this->showBoardModal = false;

        $this->dispatch('board-created', message: __('kanban.messages.board_created'));
    }

    /** Update an existing board. */
    public function updateBoard(): void
    {
        $this->validate([
            'boardForm.name'        => 'required|string|max:255',
            'boardForm.description' => 'nullable|string',
            'boardForm.color'       => 'required|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        if ($this->selectedBoard) {
            $this->selectedBoard->update($this->boardForm);
            $this->showBoardModal = false;
            $this->selectedBoard  = null;

            $this->dispatch('board-updated', message: __('kanban.messages.board_updated'));
        }
    }

    /** Delete a board. */
    public function deleteBoard(): void
    {
        if ($this->boardToDelete) {
            $this->boardToDelete->delete();
            $this->showDeleteModal = false;
            $this->boardToDelete   = null;

            $this->dispatch('board-deleted', message: __('kanban.messages.board_deleted'));
        }
    }

    /** Edit a board. */
    public function editBoard(Board $board): void
    {
        $this->selectedBoard = $board;
        $this->boardForm     = [
            'name'        => $board->name,
            'description' => $board->description,
            'color'       => $board->color,
            'is_active'   => $board->is_active,
        ];
        $this->showBoardModal = true;
    }

    /** Confirm board deletion. */
    public function confirmDelete(Board $board): void
    {
        $this->boardToDelete   = $board;
        $this->showDeleteModal = true;
    }

    /** View a board in the Kanban interface. */
    public function viewBoard(Board $board): RedirectResponse
    {
        // Redirect to KanbanApp with the selected board
        return redirect()->route('admin.app.kanban', ['board' => $board->id]);
    }

    /** Create default columns for a new board. */
    private function createDefaultColumns(Board $board): void
    {
        $defaultColumns = [
            ['name' => 'To Do', 'color' => '#6B7280', 'order' => 0],
            ['name' => 'In Progress', 'color' => '#3B82F6', 'order' => 1],
            ['name' => 'Review', 'color' => '#F59E0B', 'order' => 2],
            ['name' => 'Done', 'color' => '#10B981', 'order' => 3],
        ];

        foreach ($defaultColumns as $columnData) {
            $board->columns()->create($columnData);
        }
    }

    /** Reset board form. */
    private function resetBoardForm(): void
    {
        $this->boardForm = [
            'name'        => '',
            'description' => '',
            'color'       => '#3B82F6',
            'is_active'   => true,
        ];
        $this->selectedBoard = null;
    }

    /** Get a user's role on a board. */
    public function getUserRole(Board $board): ?string
    {
        /** @var User $user */
        $user = Auth::user();
        return $board->getUserRole($user);
    }

    /** Check if a user can edit a board. */
    public function canEdit(Board $board): bool
    {
        $role = $this->getUserRole($board);

        return in_array($role, ['owner', 'admin']);
    }

    /** Check if a user can delete a board. */
    public function canDelete(Board $board): bool
    {
        $role = $this->getUserRole($board);

        return $role === 'owner';
    }

    /** Clear search. */
    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    /** Reset filters. */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->filter = 'all';
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.admin.apps.board-list');
    }
}
