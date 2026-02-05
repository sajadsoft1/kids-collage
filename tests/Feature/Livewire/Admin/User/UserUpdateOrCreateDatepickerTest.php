<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Admin\User;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class UserUpdateOrCreateDatepickerTest extends TestCase
{
    public function test_datepicker_config_is_available(): void
    {
        $this->assertSame('jalali', config('datepicker.default_calendar'));
        $this->assertSame('Y/m/d', config('datepicker.formats.input'));
        $this->assertSame('Y-m-d', config('datepicker.formats.export'));
        $this->assertArrayHasKey('jalali', config('datepicker'));
        $this->assertArrayHasKey('months', config('datepicker.jalali'));
    }

    public function test_datepicker_helper_functions_exist(): void
    {
        $this->assertTrue(function_exists('jalali_to_gregorian'));
        $this->assertTrue(function_exists('gregorian_to_jalali'));
    }

    public function test_datepicker_helper_converts_jalali_to_gregorian(): void
    {
        $result = jalali_to_gregorian(1403, 1, 1);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('year', $result);
        $this->assertArrayHasKey('month', $result);
        $this->assertArrayHasKey('day', $result);
        $this->assertSame(2024, $result['year']);
    }

    public function test_datepicker_blade_component_renders(): void
    {
        $html = Blade::render('<x-datepicker wire:model="birth_date" placeholder="انتخاب تاریخ" />');

        $this->assertStringContainsString('dp-wrapper', $html);
        $this->assertStringContainsString('انتخاب تاریخ', $html);
        $this->assertStringContainsString('x-data="datepicker', $html);
    }
}
