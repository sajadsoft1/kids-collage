<x-card title="پرداخت‌ها" shadow separator>
    {{-- Statistics Section --}}
    <div class="grid grid-cols-2 gap-3 mb-6 lg:grid-cols-4">
        {{-- Total --}}
        <div class="p-3 rounded-lg bg-base-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-500">کل پرداخت‌ها</div>
                    <div class="mt-1 font-bold">{{ $this->paymentStats['total'] }}</div>
                    <div class="text-xs text-primary">{{ $this->formatCurrency($this->paymentStats['amount']['total']) }}
                    </div>
                </div>
                <x-icon name="o-currency-dollar" class="w-8 h-8 text-primary" />
            </div>
        </div>

        {{-- Paid --}}
        <div class="p-3 rounded-lg bg-success/10">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-500">پرداخت شده</div>
                    <div class="mt-1 font-bold text-success">{{ $this->paymentStats['paid'] }}</div>
                    <div class="text-xs text-success">{{ $this->formatCurrency($this->paymentStats['amount']['paid']) }}
                    </div>
                </div>
                <x-icon name="o-check-circle" class="w-8 h-8 text-success" />
            </div>
        </div>

        {{-- Pending --}}
        <div class="p-3 rounded-lg bg-warning/10">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-500">در انتظار</div>
                    <div class="mt-1 font-bold text-warning">{{ $this->paymentStats['pending'] }}</div>
                    <div class="text-xs text-warning">
                        {{ $this->formatCurrency($this->paymentStats['amount']['pending']) }}
                    </div>
                </div>
                <x-icon name="o-clock" class="w-8 h-8 text-warning" />
            </div>
        </div>

        {{-- Failed --}}
        <div class="p-3 rounded-lg bg-error/10">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-500">ناموفق</div>
                    <div class="mt-1 font-bold text-error">{{ $this->paymentStats['failed'] }}</div>
                    <div class="text-xs text-error">{{ $this->formatCurrency($this->paymentStats['amount']['failed']) }}
                    </div>
                </div>
                <x-icon name="o-x-circle" class="w-8 h-8 text-error" />
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-2 mb-4">
        {{-- Status Filters --}}
        <x-button label="موفق" icon="o-check-circle"
            class="btn-sm {{ $status === 'paid' ? 'btn-success' : 'btn-outline' }}"
            wire:click="filterByStatus('paid')" />

        <x-button label="در انتظار" icon="o-clock"
            class="btn-sm {{ $status === 'pending' ? 'btn-warning' : 'btn-outline' }}"
            wire:click="filterByStatus('pending')" />

        <x-button label="ناموفق" icon="o-x-circle"
            class="btn-sm {{ $status === 'failed' ? 'btn-error' : 'btn-outline' }}"
            wire:click="filterByStatus('failed')" />

        <div class="divider divider-horizontal"></div>

        {{-- Type Filters --}}
        <x-button label="آنلاین ({{ $this->paymentTypeStats['online'] }})" icon="o-credit-card"
            class="btn-sm {{ $type === 'online' ? 'btn-active' : 'btn-outline' }}"
            wire:click="filterByType('online')" />

        <x-button label="نقدی ({{ $this->paymentTypeStats['cash'] }})" icon="o-banknotes"
            class="btn-sm {{ $type === 'cash' ? 'btn-active' : 'btn-outline' }}" wire:click="filterByType('cash')" />

        <x-button label="کارت به کارت ({{ $this->paymentTypeStats['card_to_card'] }})"
            icon="o-arrow-path-rounded-square"
            class="btn-sm {{ $type === 'card_to_card' ? 'btn-active' : 'btn-outline' }}"
            wire:click="filterByType('card_to_card')" />

        @if ($status || $type)
            <x-button label="حذف فیلترها" icon="o-x-mark" class="btn-sm btn-ghost" wire:click="resetFilters" />
        @endif
    </div>

    <div class="divider"></div>

    {{-- Payments List --}}
    @if ($this->latestPayments->isEmpty())
        <div class="py-12 text-center">
            <x-icon name="o-currency-dollar" class="w-16 h-16 mx-auto text-gray-400" />
            <p class="mt-2 text-gray-500">هیچ پرداختی یافت نشد</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>شماره</th>
                        <th>کاربر</th>
                        <th>مبلغ</th>
                        <th>نوع</th>
                        <th>وضعیت</th>
                        <th>تاریخ</th>
                        <th class="w-1">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->latestPayments as $payment)
                        <tr wire:key="payment-{{ $payment->id }}" class="hover">
                            {{-- Payment ID --}}
                            <td class="font-mono">#{{ $payment->id }}</td>

                            {{-- User --}}
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="avatar">
                                        <div class="w-8 h-8 rounded-full">
                                            <img src="{{ $payment->user->getFirstMediaUrl('avatar') }}"
                                                alt="{{ $payment->user->full_name }}" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-semibold">{{ $payment->user->full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $payment->user->mobile }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Amount --}}
                            <td>
                                <div class="font-bold text-primary">
                                    {{ $this->formatCurrency($payment->amount) }}
                                </div>
                                @if ($payment->order_id)
                                    <div class="text-xs text-gray-500">سفارش #{{ $payment->order_id }}</div>
                                @endif
                            </td>

                            {{-- Type --}}
                            <td>
                                <div class="flex items-center gap-1">
                                    <x-icon :name="$this->getTypeIcon($payment->type)" class="w-4 h-4" />
                                    <span class="text-xs">{{ $payment->type->title() }}</span>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td>
                                <span class="badge {{ $this->getStatusBadgeClass($payment->status) }} badge-sm">
                                    {{ $payment->status->title() }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td>
                                <div class="text-xs text-gray-600">{{ $payment->created_at->format('Y/m/d') }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }}</div>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="flex gap-1">
                                    <x-button icon="o-eye" class="btn-xs btn-circle btn-ghost" tooltip="مشاهده جزئیات"
                                        link="{{ route('admin.payment.show', $payment) }}" />

                                    @if ($payment->order_id)
                                        <x-button icon="o-shopping-cart" class="btn-xs btn-circle btn-ghost"
                                            tooltip="مشاهده سفارش"
                                            link="{{ route('admin.order.show', $payment->order) }}" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Show More Button --}}
        @if ($this->latestPayments->count() >= $limit)
            <div class="mt-4 text-center">
                <x-button label="مشاهده همه پرداخت‌ها" icon="o-chevron-left" class="btn-sm btn-outline"
                    link="{{ $this->getMoreItemsUrl() }}" />
            </div>
        @endif
    @endif
</x-card>
