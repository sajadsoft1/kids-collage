<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Widget for displaying payment list
 *
 * @property int         $limit
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string      $status
 * @property string      $type
 * @property int|null    $user_id
 */
class PaymentListWidget extends Component
{
    public int $limit = 10;

    public ?string $start_date = null;

    public ?string $end_date = null;

    public string $status = '';

    public string $type = '';

    public ?int $user_id = null;

    /** Mount the component */
    public function mount(
        int $limit = 10,
        ?string $start_date = null,
        ?string $end_date = null,
        ?string $status = null,
        ?string $type = null,
        ?User $user = null
    ): void {
        $this->limit      = $limit;
        $this->start_date = $start_date ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $this->end_date   = $end_date ?? Carbon::now()->format('Y-m-d');
        $this->status     = $status ?? '';
        $this->type       = $type ?? '';
        $this->user_id    = $user?->id;
    }

    /** Get latest payments */
    #[Computed]
    public function latestPayments()
    {
        $query = Payment::query()
            ->with(['user', 'order'])
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->end_date);
            })
            ->when($this->status, function (Builder $query) {
                $query->where('status', $this->status);
            })
            ->when($this->type, function (Builder $query) {
                $query->where('type', $this->type);
            })
            ->when($this->user_id, function (Builder $query) {
                $query->where('user_id', $this->user_id);
            })
            ->latest('created_at')
            ->limit($this->limit);

        return $query->get();
    }

    /** Get payment statistics */
    #[Computed]
    public function paymentStats(): array
    {
        $baseQuery = Payment::query()
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->end_date);
            })
            ->when($this->user_id, function (Builder $query) {
                $query->where('user_id', $this->user_id);
            });

        return [
            'total'   => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', PaymentStatusEnum::PENDING)->count(),
            'paid'    => (clone $baseQuery)->where('status', PaymentStatusEnum::PAID)->count(),
            'failed'  => (clone $baseQuery)->where('status', PaymentStatusEnum::FAILED)->count(),
            'amount'  => [
                'total'   => (clone $baseQuery)->sum('amount'),
                'pending' => (clone $baseQuery)->where('status', PaymentStatusEnum::PENDING)->sum('amount'),
                'paid'    => (clone $baseQuery)->where('status', PaymentStatusEnum::PAID)->sum('amount'),
                'failed'  => (clone $baseQuery)->where('status', PaymentStatusEnum::FAILED)->sum('amount'),
            ],
        ];
    }

    /** Get payment type statistics */
    #[Computed]
    public function paymentTypeStats(): array
    {
        $baseQuery = Payment::query()
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->end_date);
            })
            ->when($this->user_id, function (Builder $query) {
                $query->where('user_id', $this->user_id);
            });

        return [
            'online'       => (clone $baseQuery)->where('type', PaymentTypeEnum::ONLINE)->count(),
            'cash'         => (clone $baseQuery)->where('type', PaymentTypeEnum::CASH)->count(),
            'card_to_card' => (clone $baseQuery)->where('type', PaymentTypeEnum::CARD_TO_CARD)->count(),
        ];
    }

    /** Filter payments by status */
    public function filterByStatus(string $status): void
    {
        $this->status = ($this->status === $status) ? '' : $status;
    }

    /** Filter payments by type */
    public function filterByType(string $type): void
    {
        $this->type = ($this->type === $type) ? '' : $type;
    }

    /** Reset all filters */
    public function resetFilters(): void
    {
        $this->status     = '';
        $this->type       = '';
        $this->start_date = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->end_date   = Carbon::now()->format('Y-m-d');
    }

    /** Get status badge class */
    public function getStatusBadgeClass(PaymentStatusEnum $status): string
    {
        return match ($status) {
            PaymentStatusEnum::PENDING => 'badge-warning',
            PaymentStatusEnum::PAID    => 'badge-success',
            PaymentStatusEnum::FAILED  => 'badge-error',
        };
    }

    /** Get type icon */
    public function getTypeIcon(PaymentTypeEnum $type): string
    {
        return match ($type) {
            PaymentTypeEnum::ONLINE       => 'o-credit-card',
            PaymentTypeEnum::CASH         => 'o-banknotes',
            PaymentTypeEnum::CARD_TO_CARD => 'o-arrow-path-rounded-square',
        };
    }

    /** Format currency */
    public function formatCurrency(float $amount): string
    {
        return number_format($amount, 0) . ' ' . systemCurrency();
    }

    /** Get the URL for viewing more items */
    public function getMoreItemsUrl(): string
    {
        $params = http_build_query([
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'status'     => $this->status,
            'type'       => $this->type,
            'user_id'    => $this->user_id,
        ]);

        return route('admin.payment.index') . '?' . $params;
    }

    /** Render the component */
    public function render(): View
    {
        return view('livewire.admin.widget.payment-list-widget');
    }
}
