<?php

declare(strict_types=1);

namespace App\Actions\Banner;

use App\Enums\BannerSizeEnum;
use Lorisleiva\Actions\Concerns\AsAction;

class DataBannerAction
{
    use AsAction;

    public function handle(array $payload = []): array
    {
        return [
            'size' => BannerSizeEnum::options(),
        ];
    }
}
