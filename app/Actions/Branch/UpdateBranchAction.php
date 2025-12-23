<?php

declare(strict_types=1);

namespace App\Actions\Branch;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Branch;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateBranchAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     name?:string,
     *     code?:string,
     *     status?:string,
     *     is_default?:bool,
     *     settings?:array
     * } $payload
     * @throws Throwable
     */
    public function handle(Branch $branch, array $payload): Branch
    {
        return DB::transaction(function () use ($branch, $payload) {
            // Ensure only one default branch exists
            if (Arr::get($payload, 'is_default', false) && ! $branch->is_default) {
                Branch::where('id', '!=', $branch->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            $branch->update(Arr::except($payload, ['name']));
            if (Arr::has($payload, 'name')) {
                $this->syncTranslationAction->handle($branch, Arr::only($payload, ['name']));
            }

            return $branch->refresh();
        });
    }
}
