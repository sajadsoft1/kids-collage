<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Handlers;

use App\Enums\TagTypeEnum;

class TagHandler extends BaseHandler
{
    public function handle(): array
    {
        $types = [];
        foreach (TagTypeEnum::cases() as $type) {
            $types[] = $this->option($type->value, $type->title());
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('name', __('validation.attributes.name'), self::INPUT),
            $this->add('slug', __('validation.attributes.slug'), self::INPUT),
            $this->add('type', __('validation.attributes.type'), self::SELECT, $types),
            $this->add('order_column', __('validation.attributes.order'), self::NUMBER),
            $this->add('usage_count', __('validation.attributes.usage_count'), self::NUMBER),
            $this->add('is_popular', __('validation.attributes.is_popular'), self::SELECT, [
                $this->option('yes', __('common.yes')),
                $this->option('no', __('common.no'))
            ]),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
