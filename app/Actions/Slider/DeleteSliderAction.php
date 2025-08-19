<?php

declare(strict_types=1);

namespace App\Actions\Slider;

use App\Models\Slider;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteSliderAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Slider $slider): bool
    {
        return DB::transaction(function () use ($slider) {
            return $slider->delete();
        });
    }
}
