<?php

declare(strict_types=1);

namespace App\Actions\Setting;

use App\Models\Setting;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreSettingAction
{
    use AsAction;

    public function __construct(private readonly SettingRepositoryInterface $repository) {}

    public function handle(array $payload): Setting
    {
        return DB::transaction(function () use ($payload) {
            return $this->repository->store($payload);
        });
    }
}
