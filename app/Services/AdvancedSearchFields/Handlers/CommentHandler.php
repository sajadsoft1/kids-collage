<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Handlers;

use App\Enums\BooleanEnum;
use App\Enums\YesNoEnum;
use App\Models\User;

class CommentHandler extends BaseHandler
{
    public function handle(): array
    {
        $users = [];
        foreach (User::whereHas('comments')->get() as $user) {
            $users[] = $this->option((string) $user->id, $user->full_name);
        }

        $suggests = [];
        foreach (YesNoEnum::cases() as $suggest) {
            $suggests[] = $this->option((string) $suggest->value, $suggest->title());
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('published', __('validation.attributes.published'), self::SELECT, [
                $this->option((string)BooleanEnum::ENABLE->value, BooleanEnum::ENABLE->title()),
                $this->option((string)BooleanEnum::DISABLE->value, BooleanEnum::DISABLE->title())
            ]),
            $this->add('user_id', __('validation.attributes.user'), self::SELECT, $users),
            $this->add('suggest', __('validation.attributes.suggest'), self::SELECT, $suggests),
            $this->add('rate', __('validation.attributes.rate'), self::NUMBER),
            $this->add('morphable_type', __('validation.attributes.content_type'), self::SELECT, [
                $this->option('App\\Models\\Blog', __('models.blog')),
                $this->option('App\\Models\\Portfolio', __('models.portfolio')),
            ]),
            $this->add('has_children', __('validation.attributes.has_replies'), self::SELECT, [
                $this->option('yes', __('common.yes')),
                $this->option('no', __('common.no'))
            ]),
            $this->add('is_parent', __('validation.attributes.is_parent_comment'), self::SELECT, [
                $this->option('yes', __('common.yes')),
                $this->option('no', __('common.no'))
            ]),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
