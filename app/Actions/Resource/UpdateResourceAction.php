<?php

declare(strict_types=1);

namespace App\Actions\Resource;

use App\Enums\ResourceType;
use App\Models\Resource;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateResourceAction
{
    use AsAction;

    public function __construct(
        private readonly FileService $fileService,
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
                $this->fileService->addMedia($resource, $payload['file'], $payload['type'] ?? $resource->type->value);
            }

            $resource->update($resourceData);

            return $resource->refresh();
        });
    }
}
