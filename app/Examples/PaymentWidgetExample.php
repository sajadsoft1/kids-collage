<?php

declare(strict_types=1);

namespace App\Examples;

use App\Models\User;

/**
 * Example usage of PaymentListWidget
 *
 * This file demonstrates how to use the PaymentListWidget
 * in your Blade views with different configurations.
 */
class PaymentWidgetExample
{
    /**
     * Example 1: Basic usage - Show all payments
     *
     * @livewire(admin.widget.payment-list-widget)
     */
    public function basicUsage(): string
    {
        return <<<'BLADE'
                {{-- Show last 10 payments from last 30 days --}}
                @livewire('admin.widget.payment-list-widget')
            BLADE;
    }

    /**
     * Example 2: Custom limit
     *
     * @livewire(admin.widget.payment-list-widget, ['limit=5)
     */
    public function customLimit(): string
    {
        return <<<'BLADE'
                {{-- Show only 5 payments --}}
                @livewire('admin.widget.payment-list-widget', ['limit' => 5])
            BLADE;
    }

    /** Example 3: Filter by date range */
    public function dateRangeFilter(): string
    {
        return <<<'BLADE'
                {{-- Show payments from specific date range --}}
                @livewire('admin.widget.payment-list-widget', [
                    'start_date' => '2025-01-01',
                    'end_date' => '2025-12-31'
                ])
            BLADE;
    }

    /** Example 4: Filter by status */
    public function statusFilter(): string
    {
        return <<<'BLADE'
                {{-- Show only paid payments --}}
                @livewire('admin.widget.payment-list-widget', [
                    'status' => 'paid'
                ])

                {{-- Show only pending payments --}}
                @livewire('admin.widget.payment-list-widget', [
                    'status' => 'pending'
                ])

                {{-- Show only failed payments --}}
                @livewire('admin.widget.payment-list-widget', [
                    'status' => 'failed'
                ])
            BLADE;
    }

    /** Example 5: Filter by type */
    public function typeFilter(): string
    {
        return <<<'BLADE'
                {{-- Show only online payments --}}
                @livewire('admin.widget.payment-list-widget', [
                    'type' => 'online'
                ])

                {{-- Show only cash payments --}}
                @livewire('admin.widget.payment-list-widget', [
                    'type' => 'cash'
                ])

                {{-- Show only card-to-card payments --}}
                @livewire('admin.widget.payment-list-widget', [
                    'type' => 'card_to_card'
                ])
            BLADE;
    }

    /** Example 6: Filter by user */
    public function userFilter(): string
    {
        return <<<'BLADE'
                {{-- Show payments for a specific user --}}
                @livewire('admin.widget.payment-list-widget', [
                    'user' => $user
                ])
            BLADE;
    }

    /** Example 7: Complete configuration */
    public function completeConfiguration(): string
    {
        return <<<'BLADE'
                {{-- Show last 20 pending online payments from current month for specific user --}}
                @livewire('admin.widget.payment-list-widget', [
                    'limit' => 20,
                    'start_date' => now()->startOfMonth()->format('Y-m-d'),
                    'end_date' => now()->format('Y-m-d'),
                    'status' => 'pending',
                    'type' => 'online',
                    'user' => $user
                ])
            BLADE;
    }

    /** Example 8: In Dashboard layout */
    public function dashboardLayout(): string
    {
        return <<<'BLADE'
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    {{-- Left Column - Payments --}}
                    <div>
                        @livewire('admin.widget.payment-list-widget', ['limit' => 10])
                    </div>

                    {{-- Right Column - Other Widgets --}}
                    <div class="space-y-6">
                        @livewire('admin.widget.notification-list-widget')
                        @livewire('admin.widget.latest-tickets-widget')
                    </div>
                </div>
            BLADE;
    }

    /** Example 9: User Profile - Show user's payments */
    public function userProfileLayout(): string
    {
        return <<<'BLADE'
                <div class="space-y-6">
                    {{-- User Info Section --}}
                    <x-card title="اطلاعات کاربر">
                        {{-- User details --}}
                    </x-card>

                    {{-- User's Payments --}}
                    @livewire('admin.widget.payment-list-widget', [
                        'user' => $user,
                        'limit' => 10
                    ])
                </div>
            BLADE;
    }

    /** Example 10: Available parameters */
    public function availableParameters(): array
    {
        return [
            'limit' => 'Number of payments to show (default: 10)',
            'start_date' => 'Start date for filtering (default: 30 days ago)',
            'end_date' => 'End date for filtering (default: today)',
            'status' => 'Filter by status: "paid", "pending", "failed" (default: empty)',
            'type' => 'Filter by type: "online", "cash", "card_to_card" (default: empty)',
            'user' => 'User instance to filter payments (default: null)',
        ];
    }

    /** Example 11: Statistics available in widget */
    public function availableStatistics(): array
    {
        return [
            'paymentStats' => [
                'total' => 'Total payment count',
                'pending' => 'Pending payment count',
                'paid' => 'Paid payment count',
                'failed' => 'Failed payment count',
                'amount' => [
                    'total' => 'Total amount',
                    'pending' => 'Pending amount',
                    'paid' => 'Paid amount',
                    'failed' => 'Failed amount',
                ],
            ],
            'paymentTypeStats' => [
                'online' => 'Online payment count',
                'cash' => 'Cash payment count',
                'card_to_card' => 'Card-to-card payment count',
            ],
        ];
    }

    /** Example 12: Available methods */
    public function availableMethods(): array
    {
        return [
            'filterByStatus($status)' => 'Filter payments by status',
            'filterByType($type)' => 'Filter payments by type',
            'resetFilters()' => 'Reset all filters to default',
            'getStatusBadgeClass($status)' => 'Get CSS class for status badge',
            'getTypeIcon($type)' => 'Get icon name for payment type',
            'formatCurrency($amount)' => 'Format amount with currency',
            'getMoreItemsUrl()' => 'Get URL for viewing all payments',
        ];
    }

    /** Example 13: Integration with other components */
    public function integrationExample(): string
    {
        return <<<'PHP'
                // In your Livewire component
                class OrderShow extends Component
                {
                    public Order $order;

                    public function render()
                    {
                        return view('livewire.admin.pages.order.show', [
                            // Show payments for this order
                            'payments' => $this->order->payments
                        ]);
                    }
                }

                // In your blade view
                <div>
                    {{-- Order Details --}}
                    <x-card title="جزئیات سفارش">
                        {{-- Order info --}}
                    </x-card>

                    {{-- Order Payments --}}
                    @livewire('admin.widget.payment-list-widget', [
                        'user' => $order->user,
                        'limit' => 5
                    ])
                </div>
            PHP;
    }
}
