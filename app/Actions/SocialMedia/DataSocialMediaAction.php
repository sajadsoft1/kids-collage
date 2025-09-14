<?php

namespace App\Actions\SocialMedia;

use App\Enums\SocialMediaPositionEnum;
use Lorisleiva\Actions\Concerns\AsAction;

class DataSocialMediaAction
{
    use AsAction;

    public function handle(array $payload = []): array
    {
        return [
            'position'=>SocialMediaPositionEnum::options()
        ];
    }
}
