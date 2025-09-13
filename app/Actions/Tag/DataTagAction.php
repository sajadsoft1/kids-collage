<?php

namespace App\Actions\Tag;

use App\Enums\TagTypeEnum;
use Lorisleiva\Actions\Concerns\AsAction;

class DataTagAction
{
    use AsAction;

    public function handle(array $payload = []): array
    {
        return [
            'type' => TagTypeEnum::options()
        ];
    }
}
