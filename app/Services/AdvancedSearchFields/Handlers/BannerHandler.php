<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Handlers;

use App\Enums\BooleanEnum;
use App\Enums\BannerSizeEnum;

class BannerHandler extends BaseHandler
{
    public function handle(): array
    {
        $sizes = [];
        foreach (BannerSizeEnum::cases() as $size) {
            $sizes[] = $this->option($size->value, $size->title());
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('published', __('validation.attributes.published'), self::SELECT, [
                $this->option((string)BooleanEnum::ENABLE->value, BooleanEnum::ENABLE->title()),
                $this->option((string)BooleanEnum::DISABLE->value, BooleanEnum::DISABLE->title())
            ]),
            $this->add('size', __('validation.attributes.size'), self::SELECT, $sizes),
            $this->add('click', __('validation.attributes.click_count'), self::NUMBER),
            $this->add('published_at', __('validation.attributes.published_at'), self::DATE),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
