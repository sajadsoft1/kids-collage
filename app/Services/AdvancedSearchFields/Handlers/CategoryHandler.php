<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Handlers;

use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use App\Models\Category;

class CategoryHandler extends BaseHandler
{
    public function handle(): array
    {
        $parentCategories = [];
        foreach (Category::whereNull('parent_id')->get() as $category) {
            $parentCategories[] = $this->option((string) $category->id, $category->title);
        }

        $types = [];
        foreach (CategoryTypeEnum::cases() as $type) {
            $types[] = $this->option($type->value, $type->title());
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('published', __('validation.attributes.published'), self::SELECT, [
                $this->option((string)BooleanEnum::ENABLE->value, BooleanEnum::ENABLE->title()),
                $this->option((string)BooleanEnum::DISABLE->value, BooleanEnum::DISABLE->title())
            ]),
            $this->add('parent_id', __('validation.attributes.parent_category'), self::SELECT, $parentCategories),
            $this->add('type', __('validation.attributes.type'), self::SELECT, $types),
            $this->add('ordering', __('validation.attributes.ordering'), self::NUMBER),
            $this->add('view_count', __('validation.attributes.view_count'), self::NUMBER),
            $this->add('has_children', __('validation.attributes.has_children'), self::SELECT, [
                $this->option('yes', __('common.yes')),
                $this->option('no', __('common.no'))
            ]),
            $this->add('content_count', __('validation.attributes.content_count'), self::NUMBER),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
