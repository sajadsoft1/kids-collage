<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use Livewire\Component;

class DrilldownChart extends Component
{
    public array $chartData   = [];
    public array $breadcrumbs = [];

    public function mount()
    {
        $this->loadLevel();
    }

    public function loadLevel(?string $parentId = null)
    {
        $this->chartData = $this->getData($parentId);

        if ($parentId) {
            $this->breadcrumbs[] = $parentId;
        } else {
            $this->breadcrumbs = [];
        }

        // ارسال دیتا برای JS (به‌جای تکیه بر Alpine)
        $this->dispatch('updateChart', $this->chartData);
    }

    public function goBack($index = null)
    {
        if ($index === null) {
            $this->loadLevel();
        } else {
            $targetId          = $this->breadcrumbs[$index - 1] ?? null;
            $this->breadcrumbs = array_slice($this->breadcrumbs, 0, $index);
            $this->loadLevel($targetId);
        }
    }

    private function getData(?string $parentId = null): array
    {
        if ( ! $parentId) {
            return collect(range(2020, 2025))->map(fn ($y) => [
                'id'    => "year-{$y}",
                'label' => "سال {$y}",
                'value' => rand(1000, 3000),
            ])->toArray();
        }

        if (str_starts_with($parentId, 'year-')) {
            return collect(range(1, 12))->map(fn ($m) => [
                'id'    => "{$parentId}-month-{$m}",
                'label' => "ماه {$m}",
                'value' => rand(200, 800),
            ])->toArray();
        }

        if (str_contains($parentId, 'month-')) {
            return collect(range(1, 4))->map(fn ($w) => [
                'id'    => "{$parentId}-week-{$w}",
                'label' => "هفته {$w}",
                'value' => rand(50, 150),
            ])->toArray();
        }

        if (str_contains($parentId, 'week-')) {
            return collect(range(1, 7))->map(fn ($d) => [
                'id'    => "{$parentId}-day-{$d}",
                'label' => "روز {$d}",
                'value' => rand(20, 100),
            ])->toArray();
        }

        if (str_contains($parentId, 'day-')) {
            return collect(range(0, 23))->map(fn ($h) => [
                'id'    => "{$parentId}-hour-{$h}",
                'label' => "{$h}:00",
                'value' => rand(1, 10),
            ])->toArray();
        }

        return [];
    }

    public function render()
    {
        return view('livewire.admin.widget.drilldown-chart');
    }
}
