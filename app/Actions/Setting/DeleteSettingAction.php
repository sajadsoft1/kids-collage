<?php

declare(strict_types=1);

namespace App\Actions\Setting;

use App\Models\Setting;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteSettingAction
{
    use AsAction;

    public function __construct(public readonly SettingRepositoryInterface $repository) {}

    public function handle(Setting $setting): bool
    {
        return DB::transaction(function () use ($setting) {
            return $this->repository->delete($setting);
        });
    }
}
