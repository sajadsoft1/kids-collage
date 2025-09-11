<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Handlers;
use App\Enums\BooleanEnum;
use App\Models\Category;
use App\Models\Users;

class BlogHandler extends BaseHandler
{
    public function handle(): array
    {
        $users = [];
        foreach (Users::whereHas('blog') as $user) {
            $users[] = $this->option((string) $user->id, $user->full_name);
        }

        $categories = [];
        foreach (Category::whereHas('blog') as $category) {
            $categories[] = $this->option((string) $category->id, $category->title);
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('published',__('validation.attributes.published'),self::SELECT,[
                    $this->option((string)BooleanEnum::ENABLE->value,BooleanEnum::ENABLE->title()),
                    $this->option((string)BooleanEnum::DISABLE->value,BooleanEnum::DISABLE->title())
            ]),
            $this->add('user_id',__('validation.attributes.user'),self::SELECT,$users),
            $this->add('category_id',__('validation.attributes.category_id'),self::SELECT,$categories),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
