<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Handlers;

use App\Models\User;

class BoardHandler extends BaseHandler
{
    public function handle(): array
    {
        $users = [];
        foreach (User::whereHas('boards')->get() as $user) {
            $users[] = $this->option((string) $user->id, $user->full_name);
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('name', __('validation.attributes.name'), self::INPUT),
            $this->add('is_active', __('validation.attributes.is_active'), self::SELECT, [
                $this->option('1', __('common.active')),
                $this->option('0', __('common.inactive'))
            ]),
            $this->add('system_protected', __('validation.attributes.system_protected'), self::SELECT, [
                $this->option('1', __('common.yes')),
                $this->option('0', __('common.no'))
            ]),
            $this->add('owner_id', __('validation.attributes.owner'), self::SELECT, $users),
            $this->add('has_cards', __('validation.attributes.has_cards'), self::SELECT, [
                $this->option('yes', __('common.yes')),
                $this->option('no', __('common.no'))
            ]),
            $this->add('card_count', __('validation.attributes.card_count'), self::NUMBER),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
