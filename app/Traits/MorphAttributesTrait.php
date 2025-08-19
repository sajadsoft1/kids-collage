<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

trait MorphAttributesTrait
{
    public function morphable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getMorphTypeAttribute(): string
    {
        $class = StringHelper::basename($this->morphable_type); // convert App\Models\ProductInterface to ProductInterface
        $class = Str::camel($class); // convert ProductInterface to productInterface

        return Str::kebab($class); // convert productInterface to product-interface
    }

    public function getMorphResourceAttribute(): ?JsonResource
    {
        $resource = 'App\Http\Resources\\' . Str::studly($this->morph_type) . 'Resource';
        if (class_exists($resource)) {
            return resolve($resource, ['resource' => $this->morphable]);
        }

        return null;
    }
}
