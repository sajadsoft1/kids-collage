@php
    use App\Enums\BooleanEnum;
    use App\Enums\PaymentStatusEnum;
    use App\Enums\PaymentTypeEnum;
    use App\Helpers\Constants;
    use App\Helpers\StringHelper;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-7">

        <div class="col-span-2">
            <div class="col-span-1 shadow-sm lg:col-span-2 card bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">
                        <x-select :label="trans('validation.attributes.user_id')" inline :placeholder="trans('general.please_select_an_option')" placeholder-value="0"
                            wire:model.live="user_id" :options="$users" option-label="label" option-value="value"
                            required />
                    </h2>
                    <ul>
                        <li class="flex items-center justify-between py-3 border-b border-slate-200">
                            <div class="text-sm">{{ trans('validation.attributes.mobile') }}</div>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ $this->user?->mobile ?? 'شماره همراه ثبت نشده' }}</div>
                        </li>
                        <li class="flex items-center justify-between py-3 border-b border-slate-200">
                            <div class="text-sm">{{ trans('validation.attributes.email') }}</div>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ $this->user?->email ?? 'ایمیل ثبت نشده' }}</div>
                        </li>
                        <li class="flex items-center justify-between py-3 border-b border-slate-200">
                            <div class="text-sm">{{ trans('validation.attributes.birth_date') }}</div>
                            <div class="ml-2 text-sm font-medium text-emerald-600">
                                {{ jdate($this->user?->profile?->birth_date) ?? 'تاریخ تولد ثبت نشده' }}</div>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="col-span-1 mt-5 shadow-sm lg:col-span-2 card bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">
                        <x-select :label="trans('validation.attributes.course_id')" inline :placeholder="trans('general.please_select_an_option')" placeholder-value="0"
                            wire:model.live="course_id" :options="$courses" option-label="label" option-value="value"
                            required />
                    </h2>
                    <ul>
                        <li class="flex items-center justify-between py-3 border-b border-slate-200">
                            <div class="text-sm">{{ trans('validation.attributes.price') }}</div>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ StringHelper::toCurrency($this->course?->price) }}</div>
                        </li>
                        <li class="flex items-center justify-between py-3 border-b border-slate-200">
                            <div class="text-sm">{{ trans('validation.attributes.session_count') }}</div>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ number_format($this->course?->sessions()?->count() ?? null) }}</div>
                        </li>
                        <li class="flex items-center justify-between py-3">
                            <span class="text-sm">{{ trans('validation.attributes.teacher') }}</span>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ $this->course?->teacher?->full_name ?? '-' }}</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-span-5">

            <div class="font-bold divider divider-start ">{{ trans('order.page.amount_to_pay') }}</div>
            <div class="col-span-1 mt-5 shadow-sm lg:col-span-2 card bg-base-100">
                <div class="card-body">
                    <ul>
                        <li class="flex items-center justify-between py-3 border-b border-slate-200">
                            <div class="text-sm">{{ trans('validation.attributes.discount') }}</div>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                <x-input :label="trans('validation.attributes.discount')" :placeholder="trans('validation.attributes.discount')" wire:model="discount_code"
                                    inputmode="numeric" pattern="[0-9]*" inline required>
                                    <x-slot:append>
                                        {{-- Add `join-item` to all appended elements --}}
                                        <x-button :label="trans('validation.attributes.discount')" class="join-item btn-primary"
                                            wire:click="applyDiscount" spinner wire:target="applyDiscount" />
                                    </x-slot:append>
                                </x-input>

                            </div>
                        </li>
                        <li class="flex items-center justify-between py-3 border-b border-slate-200">
                            <div class="text-sm">{{ trans('validation.attributes.discount_amount') }}</div>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ StringHelper::toCurrency($discount_amount) }}</div>
                        </li>
                        <li class="flex items-center justify-between py-3">
                            <span class="text-sm">{{ trans('validation.attributes.discount_type') }}</span>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ '-' }}</div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="font-bold divider divider-start ">{{ trans('datatable.payment_method') }}</div>
            @if (!empty($user_id) && !empty($course_id))

                @foreach ($payments as $index => $payment)
                    <div
                        class="col-span-1 p-5 mb-4 space-y-4 border shadow lg:col-span-2 card bg-base-100 border-base-300">
                        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                            <x-select :label="trans('datatable.payment_method')" :placeholder="trans('datatable.payment_method')" placeholder-value="0" :options="PaymentTypeEnum::options()"
                                option-label="label" option-value="value"
                                wire:model.live="payments.{{ $index }}.payment_type" inline required />

                            <x-input :label="trans('validation.attributes.price')" :placeholder="trans('validation.attributes.price')"
                                wire:model="payments.{{ $index }}.amount" inputmode="numeric" pattern="[0-9]*"
                                inline required />

                            <x-admin.shared.smart-datetime label="" :placeholder="trans('validation.attributes.date')"
                                wire:model="payments.{{ $index }}.paid_at" inline setNullInput="true"
                                required />

                            <x-select :label="trans('validation.attributes.status')" :placeholder="trans('validation.attributes.status')" placeholder-value="0" :options="PaymentStatusEnum::options()"
                                option-label="label" option-value="value"
                                wire:model="payments.{{ $index }}.status" inline required />
                        </div>
                        @if ($payment['payment_type'] !== PaymentTypeEnum::ONLINE->value)
                            <div class="grid grid-cols-2 gap-4">
                                @if ($payment['payment_type'] === PaymentTypeEnum::CARD_TO_CARD->value)
                                    <x-input :label="trans('order.page.last_card_digits')" :placeholder="trans('order.page.last_card_digits')"
                                        wire:model="payments.{{ $index }}.last_card_digits" x-mask="9999"
                                        inline required />

                                    <x-input :label="trans('order.page.tracking_code')" :placeholder="trans('order.page.tracking_code')"
                                        wire:model="payments.{{ $index }}.tracking_code" type="text"
                                        inputmode="numeric" pattern="[0-9]*" inline required />
                                @endif

                                <div class="col-span-2">
                                    <x-textarea :label="trans('order.page.payment_note')" :placeholder="trans('order.page.payment_note')"
                                        wire:model="payments.{{ $index }}.note" inline />
                                </div>

                            </div>
                        @endif
                    </div>
                @endforeach


                <x-button type="button" class="w-full btn btn-primary" wire:click="addPayment" spinner
                    wire:target="addPayment" icon="lucide.plus" />
            @else
                <div class="w-full">
                    @include('admin.datatable-shared.empty-table', [
                        'title' => trans('order.page.payment_schedule'),
                        'description' => trans('order.page.please_first_select_user_and_course_description'),
                        'icon' => 'lucide.calendar',
                    ])
                </div>
            @endif

        </div>





    </div>


    <x-admin.shared.form-actions />
</form>
