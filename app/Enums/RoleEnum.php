<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Role Enum
 *
 * Defines user roles for the system.
 *
 * @OA\Schema(
 *     schema="RoleEnum",
 *     @OA\Property(property="value", type="string", enum={"developer", "admin"}),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="title", type="string"),
 * ),
 */
enum RoleEnum: string
{
    use EnumToArray;

    case DEVELOPER   = 'developer';
    case ADMIN       = 'admin';

    public static function options(): array
    {
        return [
            [
                'label' => 'Developer',
                'value' => self::DEVELOPER->value,
            ],
            [
                'label' => 'Admin',
                'value' => self::ADMIN->value,
            ],
        ];
    }

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
            'value' => $this->value,
            'label' => $this->title(),
            'name'  => $this->name,
        ];
    }
}
