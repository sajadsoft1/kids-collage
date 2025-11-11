<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use Livewire\Component;

class DrilldownChart extends Component
{
    public array $chartData = [];
    public array $breadcrumbs = [];

    public function mount()
    {
        $this->loadLevel();
    }

    public function loadLevel(?string $parentId = null)
    {
        $this->chartData = $this->getData($parentId);

        if ($parentId) {
            $this->breadcrumbs[] = $this->getBreadcrumbLabel($parentId);
        } else {
            $this->breadcrumbs = [];
        }

        // ارسال دیتا برای JS (به‌جای تکیه بر Alpine)
        $this->dispatch('updateChart', data: $this->chartData);
    }

    public function goBack($index = null)
    {
        if ($index === null) {
            $this->loadLevel();
        } else {
            $targetId = $this->breadcrumbs[$index - 1] ?? null;
            $this->breadcrumbs = array_slice($this->breadcrumbs, 0, $index);
            $this->loadLevel($targetId);
        }
    }

    private function getData(?string $parentId = null): array
    {
        if ( ! $parentId) {
            return collect(range(2020, 2025))->map(fn ($y) => [
                'id' => "year-{$y}",
                'label' => "سال {$y}",
                'value' => rand(1000, 3000),
            ])->toArray();
        }

        if (str_starts_with($parentId, 'year-') && ! str_contains($parentId, 'month-')) {
            return collect(range(1, 12))->map(fn ($m) => [
                'id' => "{$parentId}-month-{$m}",
                'label' => "ماه {$m}",
                'value' => rand(200, 800),
            ])->toArray();
        }

        if (str_contains($parentId, 'month-') && ! str_contains($parentId, 'week-')) {
            return collect(range(1, 4))->map(fn ($w) => [
                'id' => "{$parentId}-week-{$w}",
                'label' => "هفته {$w}",
                'value' => rand(50, 150),
            ])->toArray();
        }

        if (str_contains($parentId, 'week-') && ! str_contains($parentId, 'day-')) {
            return collect(range(1, 7))->map(fn ($d) => [
                'id' => "{$parentId}-day-{$d}",
                'label' => "روز {$d}",
                'value' => rand(20, 100),
            ])->toArray();
        }

        if (str_contains($parentId, 'day-') && ! str_contains($parentId, 'hour-')) {
            return collect(range(0, 23))->map(fn ($h) => [
                'id' => "{$parentId}-hour-{$h}",
                'label' => "{$h}:00",
                'value' => rand(1, 10),
            ])->toArray();
        }

        return [];
    }

    private function getBreadcrumbLabel(string $id): string
    {
        if (str_starts_with($id, 'year-')) {
            $year = str_replace('year-', '', $id);

            return "سال {$year}";
        }

        if (str_contains($id, 'month-')) {
            $parts = explode('-', $id);
            $month = end($parts);

            return "ماه {$month}";
        }

        if (str_contains($id, 'week-')) {
            $parts = explode('-', $id);
            $week = end($parts);

            return "هفته {$week}";
        }

        if (str_contains($id, 'day-')) {
            $parts = explode('-', $id);
            $day = end($parts);

            return "روز {$day}";
        }

        if (str_contains($id, 'hour-')) {
            $parts = explode('-', $id);
            $hour = end($parts);

            return "{$hour}:00";
        }

        return $id;
    }

    public function render()
    {
        return view('livewire.admin.widget.drilldown-chart');
    }
}
