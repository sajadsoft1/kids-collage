<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Handlers;

class CommentHandler extends BaseHandler
{
    public function handle(): array
    {
        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
        ];
    }
}
