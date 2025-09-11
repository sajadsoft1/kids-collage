<?php

declare(strict_types=1);

namespace App\Services\AdvancedSearchFields\Handlers;
use App\Enums\BooleanEnum;
use App\Enums\GenderEnum;

class UserHandler extends BaseHandler
{
    public function handle(): array
    {
        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('status',__('validation.attributes.published'),self::SELECT,[
                    $this->option((string)BooleanEnum::ENABLE->value,BooleanEnum::ENABLE->title()),
                    $this->option((string)BooleanEnum::DISABLE->value,BooleanEnum::DISABLE->title())
            ]),

            $this->add('gender',__('validation.attributes.geder'),self::SELECT,[
                    $this->option((string)GenderEnum::MEN->value,GenderEnum::MEN->title()),
                    $this->option((string)GenderEnum::WOMEN->value,GenderEnum::WOMEN->title())
            ]),

            $this->add('user_type',__('validation.attributes.user_type'),self::SELECT,[
                    $this->option('admin','Admin'),
                    $this->option('blogger','Blogger')
            ]),

        ];
    }
}
