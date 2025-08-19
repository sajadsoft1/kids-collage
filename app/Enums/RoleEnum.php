<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    use EnumToArray;
    
    case DEVELOPER   = 'developer';
    case ADMIN       = 'admin';

    public function title(): string
    {
        return match ($this) {
            self::DEVELOPER => 'Developer',
            self::ADMIN     => 'Admin',
        };
    }
    
    public function toArray(): array
    {
        return [
            'name'  => $this->name,
            'value' => $this->value,
            'title' => $this->title(),
        ];
    }
}
