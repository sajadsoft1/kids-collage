<?php

declare(strict_types=1);

namespace App\Enums;

enum TicketDepartmentEnum: string
{
    use EnumToArray;
    
    case FINANCE_AND_ADMINISTRATION = 'finance_and_administration';   // مالی و اداری
    case SALE                       = 'Sale';                         // فروش
    case TECHNICAL                  = 'technical';                    // فنی
    
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
