<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use Livewire\Component;

class DrilldownChart extends Component
{
    public array $chartData   = [];
    public array $breadcrumbs = [];
    public bool $loading      = false;

    public function mount()
    {
        $this->loadLevel(); // سطح اول: سال‌ها
    }

    public function loadLevel(?string $parentId = null)
    {
        $this->loading = true;
        usleep(300000);

        $this->chartData = $this->getData($parentId);
        $this->loading   = false;

        if ($parentId) {
            $this->breadcrumbs[] = $parentId;
        } else {
            $this->breadcrumbs = [];
        }
    }

    public function goBack($index = null)
    {
        if ($index === null) {
            $this->loadLevel();
        } else {
            $targetId          = $this->breadcrumbs[$index] ?? null;
            $this->breadcrumbs = array_slice($this->breadcrumbs, 0, $index);
            $this->loadLevel($targetId);
        }
    }

    private function getData(?string $parentId): array
    {
        // شبیه‌سازی دیتای بازدید چندسطحی
        if ( ! $parentId) {
            // سطح ۱: سال‌ها
            return collect(range(2020, 2025))->map(fn ($y) => [
                'id'    => "year-{$y}",
                'label' => "سال {$y}",
                'value' => rand(1000, 3000),
            ])->toArray();
        }

        if (str_starts_with($parentId, 'year-')) {
            // سطح ۲: ماه‌های هر سال
            return collect(range(1, 12))->map(fn ($m) => [
                'id'    => "{$parentId}-month-{$m}",
                'label' => "ماه {$m}",
                'value' => rand(100, 500),
            ])->toArray();
        }

        if (str_contains($parentId, 'month-')) {
            // سطح ۳: هفته‌های هر ماه
            return collect(range(1, 4))->map(fn ($w) => [
                'id'    => "{$parentId}-week-{$w}",
                'label' => "هفته {$w}",
                'value' => rand(50, 150),
            ])->toArray();
        }

        if (str_contains($parentId, 'week-')) {
            // سطح ۴: روزهای هر هفته
            return collect(range(1, 7))->map(fn ($d) => [
                'id'    => "{$parentId}-day-{$d}",
                'label' => "روز {$d}",
                'value' => rand(10, 50),
            ])->toArray();
        }

        if (str_contains($parentId, 'day-')) {
            // سطح ۵: ساعت‌های هر روز
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
