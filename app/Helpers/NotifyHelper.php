<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Arr;

class NotifyHelper
{
    public static function title(array $data): string
    {
        if (in_array('title', Arr::get($data, 'translations_field', []), true)) {
            return trans($data['title']);
        }

        return Arr::get($data, 'title', '') ?? '';
    }

    public static function subTitle(array $data): string
    {
        if (in_array('sub_title', Arr::get($data, 'translations_field', []), true)) {
            return trans($data['sub_title']);
        }

        return Arr::get($data, 'sub_title', '') ?? '';
    }
}
