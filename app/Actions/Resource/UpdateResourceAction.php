<?php

declare(strict_types=1);

namespace App\Actions\Resource;

use App\Enums\ResourceType;
use App\Models\Resource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateResourceAction
{
    use AsAction;

    public function __construct(
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description?:string,
     *     type?:ResourceType,
     *     path?:string,
     *     resourceable_type?:string,
     *     resourceable_id?:int,
     *     is_public?:bool,
     *     order?:int,
     *     file?:Illuminate\Http\UploadedFile
     * }               $payload
     * @throws Throwable
     */
    public function handle(Resource $resource, array $payload): Resource
    {
        return DB::transaction(function () use ($resource, $payload) {
            $resourceData = Arr::except($payload, ['file']);

            // Handle file upload if provided
            if (isset($payload['file']) && $payload['file']) {
                // Clear existing media if updating with new file
                $resource->clearMediaCollection($resource->type->value);
                $this->handleFileUpload($resource, $payload['file'], $payload['type'] ?? $resource->type);
            }

            $resource->update($resourceData);

            return $resource->refresh();
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
