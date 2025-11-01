<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold">قوانین شرکت در آزمون</h3>
        <x-button wire:click="addGroup" label="افزودن گروه شرایط" icon="o-plus" class="btn-sm" />
    </div>

    <div class="space-y-4">
        @foreach ($rules['groups'] as $groupIndex => $group)
            <x-card class="border border-base-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium">گروه {{ $groupIndex + 1 }}</span>
                        <select wire:model.live="rules.groups.{{ $groupIndex }}.logic"
                            class="select select-sm select-bordered">
                            <option value="and">و (AND)</option>
                            <option value="or">یا (OR)</option>
                        </select>
                    </div>
                    <x-button wire:click="removeGroup({{ $groupIndex }})" label="حذف گروه" icon="o-trash"
                        class="btn-sm btn-error" />
                </div>

                <div class="space-y-3">
                    @foreach ($group['conditions'] ?? [] as $conditionIndex => $condition)
                        <div class="flex items-start gap-2 p-3 rounded-lg bg-base-200">
                            <div class="grid flex-1 grid-cols-1 gap-2 md:grid-cols-3">
                                <select
                                    wire:model.live="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.field"
                                    class="select select-bordered select-sm">
                                    @foreach ($availableFields as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>

                                <select
                                    wire:model.live="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.operator"
                                    class="select select-bordered select-sm">
                                    @foreach ($this->getOperatorsForField($condition['field'] ?? 'user_type') as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>

                                @if (($condition['field'] ?? '') === 'user_type')
                                    <select
                                        wire:model="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        class="select select-bordered select-sm">
                                        @foreach ($this->getUserTypes() as $type)
                                            <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                                        @endforeach
                                    </select>
                                @elseif(($condition['field'] ?? '') === 'enrolled_in_course')
                                    <select
                                        wire:model="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        class="select select-bordered select-sm" multiple>
                                        @foreach ($this->getCourses() as $course)
                                            <option value="{{ $course['id'] }}">{{ $course['name'] }}</option>
                                        @endforeach
                                    </select>
                                @elseif(($condition['field'] ?? '') === 'term_id')
                                    <select
                                        wire:model="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        class="select select-bordered select-sm" multiple>
                                        @foreach ($this->getTerms() as $term)
                                            <option value="{{ $term['id'] }}">{{ $term['name'] }}</option>
                                        @endforeach
                                    </select>
                                @elseif(in_array($condition['field'] ?? '', ['enrollment_date', 'created_at']))
                                    <x-input type="date"
                                        wire:model="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        class="input-sm" />
                                @elseif(($condition['field'] ?? '') === 'has_role_in_course')
                                    <select
                                        wire:model="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        class="select select-bordered select-sm">
                                        <option value="student">دانش آموز</option>
                                        <option value="parent">والد</option>
                                        <option value="teacher">استاد</option>
                                    </select>
                                @else
                                    <x-input
                                        wire:model="rules.groups.{{ $groupIndex }}.conditions.{{ $conditionIndex }}.value"
                                        class="input-sm" />
                                @endif
                            </div>
                            <x-button wire:click="removeCondition({{ $groupIndex }}, {{ $conditionIndex }})"
                                icon="o-trash" class="btn-sm btn-error btn-ghost" />
                        </div>
                    @endforeach

                    <x-button wire:click="addCondition({{ $groupIndex }})" label="افزودن شرط" icon="o-plus"
                        class="btn-sm btn-outline" />
                </div>
            </x-card>
        @endforeach
    </div>

    @if (empty($rules['groups']))
        <div class="py-8 text-center text-base-content/60">
            <p>هیچ گروه شرایطی تعریف نشده است.</p>
            <p class="mt-2 text-sm">برای شروع، یک گروه شرایط اضافه کنید.</p>
        </div>
    @endif

    <div class="flex items-center gap-2 pt-4 border-t">
        <span class="text-sm font-medium">منطق بین گروه‌ها:</span>
        <select wire:model.live="rules.group_logic" class="select select-sm select-bordered">
            <option value="or">یا (OR) - حداقل یک گروه باید برقرار باشد</option>
            <option value="and">و (AND) - همه گروه‌ها باید برقرار باشند</option>
        </select>
    </div>
</div>
