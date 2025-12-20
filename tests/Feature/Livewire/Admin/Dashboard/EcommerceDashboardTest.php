<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Admin\Dashboard;

use App\Livewire\Admin\Dashboard\EcommerceDashboard;
use Livewire\Livewire;
use Tests\TestCase;

class EcommerceDashboardTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(EcommerceDashboard::class)
            ->assertStatus(200);
    }
}
