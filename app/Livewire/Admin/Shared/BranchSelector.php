<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use Illuminate\View\View;
use Livewire\Component;

class BranchSelector extends Component
{
    public ?int $selectedBranch = null;

    /** Mount the component. */
    public function mount(?int $selectedBranch = null): void
    {
        $this->selectedBranch = $selectedBranch ?? session('selected_branch_id');
    }

    /** Get available branches for the dropdown. */
    public function getBranches(): array
    {
        // TODO: Replace with actual branch model query
        // Example: return Branch::where('is_active', true)->get()->map(fn($b) => ['value' => $b->id, 'label' => $b->name])->toArray();

        // Placeholder data - replace with actual branch data
        return [
            ['value' => 1, 'label' => 'Thunder AI'],
            ['value' => 2, 'label' => 'Business Concepts'],
            ['value' => 3, 'label' => 'KeenThemes Studio'],
        ];
    }

    /** Handle branch selection change. */
    public function updatedSelectedBranch(?int $value): void
    {
        if ($value) {
            session(['selected_branch_id' => $value]);
        }
    }

    public function render(): View
    {
        return view('livewire.admin.shared.branch-selector', [
            'branches' => $this->getBranches(),
        ]);
    }
}
