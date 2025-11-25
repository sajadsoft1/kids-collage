@php
    use App\Enums\BooleanEnum;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

        <div class="grid grid-cols-1 col-span-2 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">


                    <x-datetime :label="trans('validation.attributes.arrival_time')" wire:model.live="arrival_time" type="datetime-local" />
                    <x-datetime :label="trans('validation.attributes.leave_time')" wire:model.live="leave_time" type="datetime-local" />

                    <div class="grid grid-cols-1 gap-4 lg:col-span-2">
                        <x-select :label="trans('validation.attributes.present')" wire:model.live="present" :options="BooleanEnum::formatedCases()" option-label="label"
                            option-value="value" />
                        @if ($present == BooleanEnum::DISABLE->value)
                            <x-textarea :label="trans('validation.attributes.excuse_note')" wire:model.live="excuse_note" :placeholder="trans('validation.attributes.excuse_note')"
                                rows="4" />
                        @endif
                    </div>
                </div>
            </x-card>
        </div>

        <div class="col-span-1">
            <div class="sticky top-16">
                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit">
                    <div class="grid grid-cols-1 gap-4">
                        <x-select :label="trans('validation.attributes.enrollment_id')" wire:model.live="enrollment_id" :options="$enrollments"
                            option-label="label" option-value="value" :placeholder="trans('general.please_select_an_option')" placeholder-value=""
                            required />
                        <x-select :label="trans('validation.attributes.course_session_id')" wire:model.live="course_session_id" :options="$sessions"
                            option-label="label" option-value="value" :placeholder="trans('general.please_select_an_option')" placeholder-value=""
                            required />


                    </div>
                </x-card>
            </div>
        </div>

    </div>


    <x-admin.shared.form-actions />
</form>
