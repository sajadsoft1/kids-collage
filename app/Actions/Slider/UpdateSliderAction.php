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

class UpdateSliderAction
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
     *     published:string,
     *     ordering:string,
     *     published_at:string,
     *     expired_at:string,
     *     link:string,
     *     has_timer:string,
     *     timer_start:string,
     *     image:string,
     *     roles:array<array{
     *         type:string,
     *         value:array<int>
     *     }>,
     * }               $payload
     * @throws Throwable
     */
    public function handle(Slider $slider, array $payload): Slider
    {
        return DB::transaction(function () use ($slider, $payload) {
            $slider->update($payload);
            $this->syncTranslationAction->handle($slider, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($slider, Arr::get($payload, 'image'));

            // Step 1: Delete all existing references for the slider
            $slider->references()->delete();

            // Step 2: Prepare the new references data
            $newReferences = [];
            foreach (Arr::get($payload, 'roles', []) as $role) {
                foreach ($role['value'] as $morphId) {
                    $newReferences[] = [
                        'morphable_id'   => $morphId,
                        'morphable_type' => $role['type'],
                    ];
                }
            }

            // Step 3: Insert the new references
            foreach ($newReferences as $referenceData) {
                $slider->references()->create($referenceData);
            }

            return $slider->refresh();
        });
    }
}
