<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

trait HasSchemalessAttributes
{
    public function initializeHasSchemalessAttributes(): void
    {
        $this->casts['extra_attributes'] = SchemalessAttributes::class;
    }

    public function scopeWithExtraAttributes(): Builder
    {
        /** @phpstan-ignore-next-line */
        return $this->extra_attributes->modelScope();
    }
}
