<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\SettingEnum;
use App\Services\Setting\SettingService;
use Illuminate\Database\Seeder;
use JsonException;

class SettingSeeder extends Seeder
{
    /** Run the database seeds.
     *
     * @throws JsonException
     */
    public function run(): void
    {
        $settingService = resolve(SettingService::class);
        foreach (SettingEnum::cases() as $case) {
            $settingService->seed($case);
        }
    }
}
