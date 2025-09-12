<?php

namespace App\Actions\Category;

use Lorisleiva\Actions\Concerns\AsAction;

class DataCategoryAction
{
    use AsAction;

    public function handle(array $payload = []): array
    {
        return [
            'type' => \App\Enums\CategoryTypeEnum::options(),
        ];
    }
}
