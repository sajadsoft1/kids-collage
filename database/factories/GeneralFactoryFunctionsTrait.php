<?php

declare(strict_types=1);

namespace Database\Factories;

trait GeneralFactoryFunctionsTrait
{
    public function slugToText($string): string
    {
        return ucwords(str_replace('-', ' ', $string));
    }
}
