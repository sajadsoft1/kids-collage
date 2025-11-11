<?php

declare(strict_types=1);

namespace App\Actions\Setting;

use App\Enums\SettingEnum;
use Lorisleiva\Actions\Concerns\AsAction;

class DataSettingAction
{
    use AsAction;

    public function handle(): array
    {
        return [
            'templates' => SettingEnum::values(),
            'extra_data' => config('extra_enum.setting'),
        ];
    }
}
