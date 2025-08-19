<?php

declare(strict_types=1);

namespace App\Actions\Slider;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Slider;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreSliderAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     published:bool,
     *     ordering:int,
     *     published_at:string,
     *     expired_at:string,
     *     link:string,
     *     has_timer:bool,
     *     timer_start:string,
     *     image:string,
     *     roles:array<array{
     *         type:string,
     *         value:array<int>
     *     }>,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Slider
    {
        return DB::transaction(function () use ($payload) {
            $model = Slider::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            // Create references for roles
            $newReferences = [];
            foreach (Arr::get($payload, 'roles', []) as $role) {
                foreach ($role['value'] as $morphId) {
                    $newReferences[] = [
                        'morphable_id'   => $morphId,
                        'morphable_type' => $role['type'],
                    ];
                }
            }

            // Insert the references
            foreach ($newReferences as $referenceData) {
                $model->references()->create($referenceData);
            }

            return $model->refresh();
        });
    }
}
