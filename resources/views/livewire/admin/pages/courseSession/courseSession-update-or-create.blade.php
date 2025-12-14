@php
    use App\Enums\SessionType;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <!-- Course Session Template Selection / Display -->
            @if ($edit_mode)
                <x-input :label="trans('validation.attributes.course_session_template_id')" :value="$this->sessionTemplateTitle" readonly />
            @else
                <x-select :label="trans('validation.attributes.course_session_template_id')" :options="$courseSessionTemplates" wire:model="course_session_template_id"
                    placeholder="{{ trans('general.please_select_an_option') }}" option-value="value" option-label="label"
                    required />
            @endif

            <!-- Date -->
            <x-admin.shared.smart-datetime :label="trans('validation.attributes.date')" :placeholder="trans('validation.attributes.date')" wire-property-name="date"
                :default-date="$date" required />

            <!-- Start Time -->
            <x-input :label="trans('validation.attributes.start_time')" type="time" wire:model="start_time" />

            <!-- End Time -->
            <x-input :label="trans('validation.attributes.end_time')" type="time" wire:model="end_time" />

            <!-- Session Type -->
            <x-select :label="trans('validation.attributes.session_type')" :options="$sessionTypeOptions" wire:model.live="session_type" option-value="value"
                option-label="label" required />

            <!-- Room Selection (shown for in-person and hybrid sessions) -->
            @if (in_array($session_type, ['in-person', 'hybrid']))
                <x-select :label="trans('validation.attributes.room_id')" :options="$rooms" wire:model="room_id"
                    placeholder="{{ trans('general.please_select_an_option') }}" option-value="value"
                    option-label="label" />
            @endif

            <!-- Status -->
            <x-select :label="trans('validation.attributes.status')" :options="$sessionStatusOptions" wire:model="status" option-value="value"
                option-label="label" required />

            <!-- Meeting Link (shown for online and hybrid sessions) -->
            @if (in_array($session_type, ['online', 'hybrid']))
                <x-input :label="trans('validation.attributes.meeting_link')" wire:model="meeting_link" type="url" placeholder="https://..." />
            @endif

            <!-- Recording Link -->
            <x-input :label="trans('validation.attributes.recording_link')" wire:model="recording_link" type="url" placeholder="https://..." />
        </div>
    </x-card>

    <x-admin.shared.form-actions />
</form>
