<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold flex items-center justify-center gap-2">قوانین شرکت در آزمون
            <div class="flex items-center justify-center w-6" wire:loading>
                <x-loading class="progress-primary" />
            </div>
        </h3>
        <x-button wire:click="addGroup" label="افزودن گروه شرایط" icon="o-plus" class="btn-sm" spinner
            wire:target="addGroup" />
    </div>

    <div class="space-y-4">
        @foreach ($rules['groups'] as $groupIndex => $group)
            <x-card class="border border-base-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium">گروه {{ $groupIndex + 1 }}</span>
                        <x-select wire:model.live="rules.groups.{{ $groupIndex }}.logic" option-label="value"
                            option-value="key" :options="[
                                ['key' => 'and', 'value' => trans('exam.page.builder.group.rules.and')],
                                ['key' => 'or', 'value' => trans('exam.page.builder.group.rules.or')],
                            ]">
                        </x-select>
                    </div>
                    <x-button wire:click="removeGroup({{ $groupIndex }})" :label="trans('exam.page.builder.remove_group')" icon="o-trash"
                        class="btn-sm btn-error" spinner wire:target="removeGroup({{ $groupIndex }})" />
                </div>

                <div class="space-y-3">
                    @foreach ($group['conditions'] ?? [] as $conditionIndex => $condition)
                        <div class="flex items-start gap-2 p-3 rounded-lg">
                            <div class="grid flex-1 grid-cols-1 gap-2 md:grid-cols-3">
                                <x-select class="input-sm"
                                    wire:model.live="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.field"
                                    :placeholder="trans('general.please_select_an_option')" placeholder-value="" option-label="label" option-value="value"
                                    :options="$this->getFieldOptions()" />

                                <x-select class="input-sm"
                                    wire:model.live="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.operator"
                                    wire:loading.attr="disabled"
                                    wire:target="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.field"
                                    :placeholder="trans('general.please_select_an_option')" placeholder-value="" option-label="label" option-value="value"
                                    :options="$this->getOperatorsForField($condition['field'] ?? 'user_type')">
                                    <x-slot:prepend>
                                        <div class="flex items-center justify-center w-6">
                                            <x-icon name="o-adjustments-horizontal" class="w-4 h-4 text-base-content/50"
                                                wire:loading.remove
                                                wire:target="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.field" />
                                            <x-loading class="loading-infinity loading-sm" wire:loading.flex
                                                wire:target="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.field" />
                                        </div>
                                    </x-slot:prepend>
                                </x-select>

                                @if (($condition['field'] ?? '') === 'user_type')
                                    <x-select class="input-sm" wire:loading.attr="disabled"
                                        wire:target="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.field"
                                        wire:model.live="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        :placeholder="trans('general.please_select_an_option')" placeholder-value="" option-label="label" option-value="value"
                                        :options="$this->getUserTypes()" />
                                @elseif(($condition['field'] ?? '') === 'enrolled_in_course')
                                    <x-select class="input-sm" wire:loading.attr="disabled"
                                        wire:target="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.field"
                                        wire:model.live="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        :placeholder="trans('general.please_select_an_option')" placeholder-value="" option-label="label" option-value="value"
                                        :options="$this->getCourses()" />
                                @elseif(($condition['field'] ?? '') === 'term_id')
                                    <x-choices-offline class="input-sm" wire:loading.attr="disabled"
                                        wire:target="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.field"
                                        wire:model.live="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        :placeholder="trans('general.please_select_an_option')" option-label="label" option-value="value" :options="$this->getTerms()"
                                        searchable single />
                                @elseif(in_array($condition['field'] ?? '', ['enrollment_date', 'created_at']))
                                    <x-input type="date" wire:loading.attr="disabled"
                                        wire:target="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.field"
                                        wire:model.live="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        class="input-sm" />
                                @elseif(($condition['field'] ?? '') === 'has_role_in_course')
                                    <x-select class="input-sm" wire:loading.attr="disabled"
                                        wire:target="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.field"
                                        wire:model.live="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        :placeholder="trans('general.please_select_an_option')" placeholder-value="" option-label="label"
                                        option-value="value" :options="$this->getRoles()" />
                                @else
                                    <x-input wire:loading.attr="disabled"
                                        wire:target="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.field"
                                        wire:model.live="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        class="input-sm" />
                                @endif
                            </div>
                            <x-button wire:click="removeCondition({{ $groupIndex }}, {{ $conditionIndex }})" spinner
                                wire:target="removeCondition({{ $groupIndex }}, {{ $conditionIndex }})"
                                icon="o-trash" class="btn-sm btn-error btn-ghost" />
                        </div>
                    @endforeach

                    <x-button wire:click="addCondition({{ $groupIndex }})" label="افزودن شرط" icon="o-plus" spinner
                        wire:target="addCondition({{ $groupIndex }})" class="btn-sm btn-outline" />
                </div>
            </x-card>
        @endforeach
    </div>

    @if (empty($rules['groups']))
        @include('components.admin.shared.empty-view', [
            'title' => 'هیچ گروه شرایطی تعریف نشده است.',
            'description' => 'برای شروع، یک گروه شرایط اضافه کنید.',
        ])
    @endif

    <div class="flex items-center gap-2 pt-4 border-t">
        <span class="text-sm font-medium">منطق بین گروه‌ها:</span>
        <x-select wire:model.live="rules.group_logic" :options="[
            ['key' => 'or', 'value' => 'یا (OR) - حداقل یک گروه باید برقرار باشد'],
            ['key' => 'and', 'value' => 'و (AND) - همه گروه‌ها باید برقرار باشند'],
        ]" option-label="value" option-value="key">
        </x-select>
    </div>
</div>
