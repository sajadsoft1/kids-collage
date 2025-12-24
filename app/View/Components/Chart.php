<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Chart Component with One-Way Data Binding.
 *
 * This is a local override of Mary UI's Chart component that accepts
 * chart data directly as a prop instead of using @entangle. This prevents
 * the "toJSON" error that occurs when Chart.js modifies the settings object
 * and Livewire tries to sync it back to the server.
 */
class Chart extends Component
{
    public string $uuid;

    public array $chartData;

    public function __construct(
        public ?string $id = null,
        array $data = [],
    ) {
        $this->uuid = 'chart-' . md5(serialize($id)) . ($id ?? uniqid());
        $this->chartData = $data;
    }

    public function render(): View
    {
        return view('components.chart', [
            'uuid' => $this->uuid,
            'chartData' => $this->chartData,
        ]);
    }
}
