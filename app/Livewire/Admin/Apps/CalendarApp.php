<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Apps;

use App\Services\LivewireTemplates\CalendarTemplate;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarApp extends CalendarTemplate
{
    public function events(): Collection
    {
        return collect([
            [
                'id' => 1,
                'title' => 'Breakfast',
                'description' => 'Pancakes! 🥞',
                'date' => Carbon::today(),
            ],
            [
                'id' => 2,
                'title' => 'Meeting with Pamela',
                'description' => 'Work stuff',
                'date' => Carbon::tomorrow(),
            ],
        ]);
    }
}
