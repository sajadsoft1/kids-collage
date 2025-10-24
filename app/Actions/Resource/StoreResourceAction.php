<?php

declare(strict_types=1);

namespace App\Actions\Resource;

use App\Enums\ResourceType;
use App\Models\Resource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreResourceAction
{
    use AsAction;

    public function __construct(
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description?:string,
     *     type:ResourceType,
     *     path?:string,
     *     resourceable_type?:string,
     *     resourceable_id?:int,
     *     is_public?:bool,
     *     order?:int,
     *     file?:Illuminate\Http\UploadedFile
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Resource
    {
        return DB::transaction(function () use ($payload) {
            $resourceData = Arr::except($payload, ['file']);

            // Set default values
            $resourceData['order'] ??= 0;
            $resourceData['is_public'] ??= true;

            $model = Resource::create($resourceData);

            // Handle file upload if provided
            if (isset($payload['file']) && $payload['file']) {
                $this->handleFileUpload($model, $payload['file'], $payload['type']);
            }

            return $model->refresh();
        });
    }

    private function handleFileUpload(Resource $resource, $file, ResourceType $type): void
    {
        $media = $resource->addMediaFromRequest('file')
            ->toMediaCollection($type->value);

        // Update path with media URL
        $resource->update(['path' => $media->getUrl()]);

        // Store file metadata
        $resource->extra_attributes = array_merge($resource->extra_attributes->toArray(), [
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);
        $resource->save();
    }
}
