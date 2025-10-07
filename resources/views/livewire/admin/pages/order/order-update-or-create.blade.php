@php use App\Enums\BooleanEnum;use App\Enums\PaymentStatusEnum;use App\Enums\PaymentTypeEnum;use App\Helpers\Constants;use App\Helpers\StringHelper; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">

        <div class="col-span-1 lg:col-span-2 card card-side bg-base-100 shadow-sm mt-5">
            <figure>
                <img
                        class="w-50 h-full object-cover"
                        src="{{$this->user?->getFirstMediaUrl('avatar', Constants::RESOLUTION_512_SQUARE) ?? asset('assets/images/default/01@2x.png')}}"
                        alt="Movie"/>
            </figure>
            <div class="card-body">
                <h2 class="card-title">
                    <x-select :label="trans('validation.attributes.user_id')"
                              inline
                              :placeholder="trans('general.please_select_an_option')"
                              placeholder-value="0"
                              wire:model.live="user_id" :options="$users" option-label="label" option-value="value" required/>
                </h2>
                <ul>
                    <li class="flex items-center justify-between py-3 border-b border-slate-200">
                        <div class="text-sm">{{trans('validation.attributes.mobile')}}</div>
                        <div class="text-sm font-medium text-slate-800 ml-2">{{$this->user?->mobile??'شماره همراه ثبت نشده'}}</div>
                    </li>
                    <li class="flex items-center justify-between py-3 border-b border-slate-200">
                        <div class="text-sm">{{trans('validation.attributes.email')}}</div>
                        <div class="text-sm font-medium text-slate-800 ml-2">{{$this->user?->email ?? 'ایمیل ثبت نشده'}}</div>
                    </li>
                    <li class="flex items-center justify-between py-3 border-b border-slate-200">
                        <div class="text-sm">{{trans('validation.attributes.birth_date')}}</div>
                        <div class="text-sm font-medium text-emerald-600 ml-2">{{jdate($this->user?->profile?->birth_date) ?? 'تاریخ تولد ثبت نشده'}}</div>
                    </li>
                </ul>
            </div>
        </div>


        <div class="col-span-1 lg:col-span-2 card card-side bg-base-100 shadow-sm mt-5">
            <figure>
                <img
                        class="w-50 h-full object-cover"
                        src="{{$this->course?->template?->getFirstMediaUrl('image', Constants::RESOLUTION_512_SQUARE)??asset('assets/images/default/01@2x.png')}}"
                        alt="Movie"/>
            </figure>
            <div class="card-body">
                <h2 class="card-title">
                    <x-select :label="trans('validation.attributes.course_id')"
                              inline
                              :placeholder="trans('general.please_select_an_option')"
                              placeholder-value="0"
                              wire:model.live="course_id" :options="$courses" option-label="label" option-value="value" required/>
                </h2>
                <ul>
                    <li class="flex items-center justify-between py-3 border-b border-slate-200">
                        <div class="text-sm">{{trans('validation.attributes.price')}}</div>
                        <div class="text-sm font-medium text-slate-800 ml-2">{{StringHelper::toCurrency($this->course?->price)}}</div>
                    </li>
                    <li class="flex items-center justify-between py-3 border-b border-slate-200">
                        <div class="text-sm">{{trans('validation.attributes.session_count')}}</div>
                        <div class="text-sm font-medium text-slate-800 ml-2">{{number_format($this->course?->sessions()?->count() ??null)}}</div>
                    </li>
                    <li class="flex items-center justify-between py-3">
                        <span class="text-sm">{{trans('validation.attributes.teacher')}}</span>
                        <div class="text-sm font-medium text-slate-800 ml-2">{{$this->course?->teacher?->full_name??'-'}}</div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-span-4">
            <div class="divider divider-start font-bold ">{{trans('datatable.payment_method')}}</div>
        </div>
        @foreach($payments as $index => $payment)

            <div class="col-span-1 lg:col-span-2 card bg-base-100 border-base-300 border p-5 shadow space-y-4">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-select :label="trans('datatable.payment_method')"
                              :placeholder="trans('datatable.payment_method')"
                              placeholder-value="0"
                              :options="PaymentTypeEnum::options()"
                              option-label="label"
                              option-value="value"
                              inline
                              required/>

                    <x-input :label="trans('validation.attributes.price')"
                             :placeholder="trans('validation.attributes.price')"
                             inputmode="numeric"
                             pattern="[0-9]*"
                             inline
                             required/>

                    <x-admin.shared.smart-datetime label=""
                                                   :placeholder="trans('validation.attributes.date')"
                                                   inline
                                                   required/>

                    <x-select :label="trans('validation.attributes.status')"
                              :placeholder="trans('validation.attributes.status')"
                              placeholder-value="0"
                              :options="PaymentStatusEnum::options()"
                              option-label="label"
                              option-value="value"
                              inline
                              required/>
                </div>
               @if($payment['payment_type'] !== PaymentTypeEnum::ONLINE->value)
                    <div class="grid grid-cols-2 gap-4">
                        @if($payment['payment_type'] === PaymentTypeEnum::CARD_TO_CARD->value)
                            <x-input :label="trans('order.page.last_card_digits')"
                                     :placeholder="trans('order.page.last_card_digits')"
                                     x-mask="9999"
                                     inline
                                     required/>

                            <x-input :label="trans('order.page.tracking_code')"
                                     :placeholder="trans('order.page.tracking_code')"
                                     type="text"
                                     inputmode="numeric"
                                     pattern="[0-9]*"
                                     inline
                                     required/>
                        @endif

                        <div class="col-span-2">
                            <x-textarea
                                    :label="trans('order.page.payment_note')"
                                    :placeholder="trans('order.page.payment_note')"
                                    inline/>
                        </div>

                    </div>
               @endif
            </div>
        @endforeach


        <div class="col-span-4">
            <x-button type="button" class="btn btn-outline btn-primary mt-5" wire:click="addPayment" spinner wire:target="addPayment" icon="lucide.plus"/>
        </div>
    </div>


    <x-admin.shared.form-actions/>
</form>
