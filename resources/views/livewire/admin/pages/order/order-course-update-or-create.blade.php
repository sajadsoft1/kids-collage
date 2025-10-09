@php
    use App\Enums\BooleanEnum;
    use App\Enums\PaymentStatusEnum;
    use App\Enums\PaymentTypeEnum;
    use App\Helpers\Constants;
    use App\Helpers\StringHelper;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-7">

        <div class="col-span-2">
            <div class="font-bold divider divider-start">{{ trans('validation.attributes.user_id') }}</div>
            <div class="col-span-1 shadow-sm lg:col-span-2 card bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">
                        <x-select inline :placeholder="trans('general.please_select_an_option')" placeholder-value="0" wire:model.live="user_id"
                                  :options="$users" option-label="label" option-value="value" required :disabled="$edit_mode"/>
                    </h2>
                    <ul>
                        <li class="flex justify-between items-center py-3 border-b border-slate-200">
                            <div class="text-sm">{{ trans('validation.attributes.mobile') }}</div>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ $this->user?->mobile ?? 'شماره همراه ثبت نشده' }}</div>
                        </li>
                        <li class="flex justify-between items-center py-3 border-b border-slate-200">
                            <div class="text-sm">{{ trans('validation.attributes.email') }}</div>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ $this->user?->email ?? 'ایمیل ثبت نشده' }}</div>
                        </li>
                        <li class="flex justify-between items-center py-3">
                            <div class="text-sm">{{ trans('validation.attributes.birth_date') }}</div>
                            <div class="ml-2 text-sm font-medium text-emerald-600">
                                {{ $this->user?->profile?->birth_date ?jdate($this->user?->profile?->birth_date) : 'تاریخ تولد ثبت نشده' }}</div>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="font-bold divider divider-start">{{ trans('validation.attributes.course_id') }}</div>
            @foreach($items as $index=>$item)
                <div class="col-span-1 mt-5 shadow-sm lg:col-span-2 card bg-base-100">
                    <div class="card-body">
                        <h2 class="card-title">
                            <x-select inline :placeholder="trans('general.please_select_an_option')" placeholder-value="0" wire:model.live="items.{{ $index }}.itemable_id"
                                      :options="$courses" option-label="label" option-value="value" required :disabled="$edit_mode"/>
                        </h2>
                        <ul>
                            <li class="flex justify-between items-center py-3 border-b border-slate-200">
                                <div class="text-sm">{{ trans('validation.attributes.price') }}</div>
                                <div class="ml-2 text-sm font-medium text-slate-800">
                                    {{ StringHelper::toCurrency($item['price']) }}</div>
                            </li>
                            <li class="flex justify-between items-center py-3 border-b border-slate-200">
                                <div class="text-sm">{{ trans('validation.attributes.session_count') }}</div>
                                <div class="ml-2 text-sm font-medium text-slate-800">
                                    {{ number_format($item['session_count']) }}</div>
                            </li>
                            <li class="flex justify-between items-center py-3">
                                <span class="text-sm">{{ trans('validation.attributes.teacher') }}</span>
                                <div class="ml-2 text-sm font-medium text-slate-800">
                                    {{ $item['teacher'] }}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach

            <div class="font-bold divider divider-start">{{ trans('validation.attributes.note') }}</div>
            <x-textarea :placeholder="trans('validation.attributes.note')" wire:model="note"/>
        </div>

        <div class="col-span-5">

            <div class="font-bold divider divider-start">{{ trans('order.page.amount_to_pay') }}</div>
            <div class="col-span-1 mt-5 shadow-sm lg:col-span-2 card bg-base-100">
                <div class="card-body">
                    <ul>
                        <li class="flex justify-between items-center py-3 border-b border-slate-200">
                            <div class="text-sm">{{ trans('validation.attributes.discount') }}</div>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                <x-input :label="trans('validation.attributes.discount')" :placeholder="trans('validation.attributes.discount')" wire:model="discount_code" inline required :disabled="$edit_mode || !$this->payableAmount">
                                    <x-slot:append>
                                        {{-- Add `join-item` to all appended elements --}}
                                        <x-button :label="trans('validation.attributes.discount')" class="join-item btn-primary"
                                                  wire:click="applyDiscount" spinner wire:target="applyDiscount"
                                                  :disabled="$edit_mode || !$this->payableAmount"/>
                                    </x-slot:append>
                                </x-input>

                            </div>
                        </li>
                        <li class="flex justify-between items-center py-3 border-b border-slate-200">
                            <div class="text-sm">{{ trans('validation.attributes.discount_amount') }}
                                @if($discount)
                                    <span class="badge badge-primary">{{$discount->type->title()}}</span>
                                @endif
                            </div>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ StringHelper::toCurrency($this->discountAmount) }}
                            </div>
                        </li>
                        <li class="flex justify-between items-center py-3 border-b border-slate-200">
                            <span class="text-sm">{{ trans('validation.attributes.pure_amount') }}</span>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ StringHelper::toCurrency(collect($items)->sum('price')) }}</div>
                        </li>
                        <li class="flex justify-between items-center py-3">
                            <span class="text-sm">{{ trans('validation.attributes.payable_amount') }}</span>
                            <div class="ml-2 text-sm font-medium text-slate-800">
                                {{ StringHelper::toCurrency($this->payableAmount) }}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="font-bold divider divider-start">{{ trans('datatable.payment_method') }}</div>
            @if (!empty($user_id) && $this->payableAmount)

                @foreach ($payments as $index => $payment)
                    <div
                            class="col-span-1 p-5 mb-4 space-y-4 border shadow lg:col-span-2 card bg-base-100 border-base-300">
                        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                            <x-select :label="trans('datatable.payment_method')" :placeholder="trans('datatable.payment_method')" placeholder-value="0" :options="PaymentTypeEnum::options()"
                                      option-label="label" option-value="value" :disabled="in_array($payment['status'] ,[PaymentStatusEnum::PAID->value,PaymentStatusEnum::FAILED->value])"
                                      wire:model.live="payments.{{ $index }}.type" inline required/>

                            <x-input :label="trans('validation.attributes.price')" :placeholder="trans('validation.attributes.price')" :disabled="in_array($payment['status'] ,[PaymentStatusEnum::PAID->value,PaymentStatusEnum::FAILED->value])"
                                     wire:model="payments.{{ $index }}.amount" inputmode="numeric" pattern="[0-9]*"
                                     inline required/>

                            <x-admin.shared.smart-datetime label="" :placeholder="trans('validation.attributes.date')" :disabled="in_array($payment['status'] ,[PaymentStatusEnum::PAID->value,PaymentStatusEnum::FAILED->value])"
                                                           wire-property-name="payments.{{ $index }}.scheduled_date"
                                                           :defaultDate="$payment['scheduled_date']"
                                                           inline :setNullInput="empty($payment['scheduled_date'])"
                                                           required/>

                            <x-select :label="trans('validation.attributes.status')" :placeholder="trans('validation.attributes.status')" placeholder-value="0" :options="PaymentStatusEnum::options()"
                                      option-label="label" option-value="value" :disabled="in_array($payment['status'] ,[PaymentStatusEnum::PAID->value,PaymentStatusEnum::FAILED->value])"
                                      wire:model.live="payments.{{ $index }}.status" inline required/>
                        </div>
                        @if ($payment['type'] !== PaymentTypeEnum::ONLINE->value)
                            <div class="grid grid-cols-2 gap-4">
                                @if ($payment['type'] === PaymentTypeEnum::CARD_TO_CARD->value)
                                    <x-input :label="trans('order.page.last_card_digits')" :placeholder="trans('order.page.last_card_digits')" :disabled="$payment['status'] === PaymentStatusEnum::PAID->value"
                                             wire:model="payments.{{ $index }}.last_card_digits" x-mask="9999"
                                             inline required/>

                                    <x-input :label="trans('order.page.tracking_code')" :placeholder="trans('order.page.tracking_code')" :disabled="$payment['status'] === PaymentStatusEnum::PAID->value"
                                             wire:model="payments.{{ $index }}.tracking_code" type="text"
                                             inputmode="numeric" pattern="[0-9]*" inline required/>
                                @endif

                                <div class="col-span-2">
                                    <x-textarea :label="trans('order.page.payment_note')" :placeholder="trans('order.page.payment_note')" :disabled="$payment['status'] === PaymentStatusEnum::PAID->value"
                                                wire:model="payments.{{ $index }}.note" inline/>
                                </div>

                            </div>
                        @endif
                    </div>
                @endforeach


                <x-button type="button" class="w-full btn btn-primary" wire:click="addPayment" spinner
                          wire:target="addPayment" icon="lucide.plus" :label="trans('order.page.add_payment')"/>
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


    <x-admin.shared.form-actions/>
</form>
