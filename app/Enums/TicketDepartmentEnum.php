<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Ticket Department Enum
 *
 * Defines department options for ticket routing.
 *
 * @OA\Schema(
 *     schema="TicketDepartmentEnum",
 *     @OA\Property(property="value", type="string", enum={"finance_and_administration", "Sale", "technical"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 */
enum TicketDepartmentEnum: string
{
    use EnumToArray;

    case FINANCE_AND_ADMINISTRATION = 'finance_and_administration';   // مالی و اداری
    case SALE                       = 'Sale';                         // فروش
    case TECHNICAL                  = 'technical';                    // فنی

    public static function options(): array
    {
        return [
            [
                'label' => __('ticket.enum.department.finance_and_administration'),
                'value' => self::FINANCE_AND_ADMINISTRATION->value,
            ],
            [
                'label' => __('ticket.enum.department.Sale'),
                'value' => self::SALE->value,
            ],
            [
                'label' => __('ticket.enum.department.technical'),
                'value' => self::TECHNICAL->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::FINANCE_AND_ADMINISTRATION => __('ticket.enum.department.finance_and_administration'),
            self::SALE                       => __('ticket.enum.department.Sale'),
            self::TECHNICAL                  => __('ticket.enum.department.technical'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
        ];
    }
}
