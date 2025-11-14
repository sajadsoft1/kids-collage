@php
    use App\Helpers\Constants;
    use App\Enums\CourseStatusEnum;
    use App\Enums\SessionType;
@endphp
<div class="h-full">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />


    <x-card>
        <x-steps wire:model="runningStep" stepper-classes="w-full p-1 md:p-5" steps-color="step-primary">
            <x-step step="1" text="اطلاعات دوره" class="!px-0">

                <div class="p-6 space-y-6">
                    <!-- Course Header -->
                    <div class="flex items-start space-x-4">
                        @if ($courseTemplate->getFirstMediaUrl('image', Constants::RESOLUTION_854_480))
                            <img src="{{ $courseTemplate->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}"
                                alt="{{ $courseTemplate->title }}" class="object-cover w-24 h-24 rounded-lg">
                        @endif
                        <div class="flex-1">
                            <h2 class="mb-2 text-2xl font-bold text-slate-800">{{ $courseTemplate->title }}</h2>
                            <p class="text-sm text-slate-600">{{ $courseTemplate->description }}</p>
                        </div>
                    </div>

                    <!-- Course Information -->
                    <section>
                        <h3 class="mb-4 text-xl font-bold leading-snug text-slate-800">
                            {{ trans('general.page_sections.data') }}</h3>
                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            @foreach ($informations as $information)
                                <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                    <span class="text-sm font-medium text-slate-800">{{ $information['label'] }}</span>
                                    <span class="text-sm text-slate-600">{{ $information['value'] }}</span>
                                </div>
                            @endforeach

                        </div>
                    </section>

                    <!-- Prerequisites -->
                    <section>
                        <h3 class="mb-4 text-xl font-bold leading-snug text-slate-800">پیش‌نیازها</h3>
                        <div class="space-y-2">
                            @forelse ($prerequisites as $prerequisite)
                                <div class="flex items-center space-x-2">
                                    <x-icon name="o-check" class="w-4 h-4 text-green-500" />
                                    <span class="text-sm text-slate-600">{{ $prerequisite['label'] }}</span>
                                </div>
                            @empty
                                <x-alert title="هیچ پیش‌نیازی تعریف نشده" icon="lucide.brain-cog"
                                    class="alert-warning" />
                            @endforelse
                        </div>
                    </section>

                    <!-- Course Description -->
                    @if ($courseTemplate->body)
                        <section>
                            <h3 class="mb-4 text-xl font-bold leading-snug text-slate-800">
                                {{ trans('validation.attributes.description') }}</h3>
                            <div class="max-w-none prose">
                                {!! $courseTemplate->body !!}
                            </div>
                        </section>
                    @else
                        <div class="flex items-center space-x-2">
                            <x-icon name="o-user" class="w-4 h-4 text-red-500" />
                            <span class="text-sm text-slate-600">هیچ توضیحی تعریف نشده</span>
                        </div>
                    @endif

                </div>

            </x-step>
            <x-step step="2" text="اطلاعات اجرایی" class="!px-0">

                <div class="p-6 space-y-6">

                    <x-admin.shared.inline-input :label="trans('validation.attributes.capacity')" :info="trans('course.page.runer.capacity_info')">
                        <x-input class="text-sm text-slate-600 min-w-20 lg:min-w-40" wire:model="capacity" required
                            icon="lucide.contact-round" min="1" suffix="نفر" />
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.price')" :info="trans('course.page.runer.price_info')">
                        <x-input class="text-sm text-slate-600 min-w-20 lg:min-w-32" wire:model="price" required
                            type="number" min="0" icon="lucide.hand-coins" :suffix="systemCurrency()" />
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.status')" :info="trans('course.page.runer.status_info')">
                        <x-select class="text-sm text-slate-600 lg:min-w-60" wire:model="status" required
                            :options="CourseStatusEnum::runerOptions()" option-value="value" option-label="label" />
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.term_id')" :info="trans('course.page.runer.term_info')">
                        <x-select class="text-sm text-slate-600 lg:min-w-60" wire:model.live="term" required
                            placeholder="{{ trans('course.validations.select_a_term') }}" placeholder-value="0"
                            :options="$terms" option-value="value" option-label="label" />
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.week_days')" :info="trans('course.page.runer.week_days_info')">
                        <div class="space-y-3">
                            <!-- Quick action buttons -->
                            <div class="flex gap-2 mb-3">
                                <x-button icon="lucide.check-check" label="انتخاب همه" type="button"
                                    wire:click="selectAllWeekDays" spinner="selectAllWeekDays"
                                    class="btn btn-sm btn-outline btn-primary" />
                                <x-button icon="lucide.trash" label="پاک کردن همه" type="button"
                                    wire:click="clearWeekDays" spinner="clearWeekDays"
                                    class="btn btn-sm btn-outline btn-secondary" />
                            </div>

                            <!-- Week days button group -->
                            <div class="flex flex-wrap gap-2">
                                <x-button :label="$dayNames['1']" type="button" wire:click="toggleWeekDay('1')"
                                    spinner="toggleWeekDay('1')"
                                    class="btn btn-sm {{ in_array('1', $week_days) ? 'btn-primary' : 'btn-outline btn-primary' }}" />
                                <x-button :label="$dayNames['2']" type="button" wire:click="toggleWeekDay('2')"
                                    spinner="toggleWeekDay('2')"
                                    class="btn btn-sm {{ in_array('2', $week_days) ? 'btn-primary' : 'btn-outline btn-primary' }}" />
                                <x-button :label="$dayNames['3']" type="button" wire:click="toggleWeekDay('3')"
                                    spinner="toggleWeekDay('3')"
                                    class="btn btn-sm {{ in_array('3', $week_days) ? 'btn-primary' : 'btn-outline btn-primary' }}" />
                                <x-button :label="$dayNames['4']" type="button" wire:click="toggleWeekDay('4')"
                                    spinner="toggleWeekDay('4')"
                                    class="btn btn-sm {{ in_array('4', $week_days) ? 'btn-primary' : 'btn-outline btn-primary' }}" />
                                <x-button :label="$dayNames['5']" type="button" wire:click="toggleWeekDay('5')"
                                    spinner="toggleWeekDay('5')"
                                    class="btn btn-sm {{ in_array('5', $week_days) ? 'btn-primary' : 'btn-outline btn-primary' }}" />
                                <x-button :label="$dayNames['6']" type="button" wire:click="toggleWeekDay('6')"
                                    spinner="toggleWeekDay('6')"
                                    class="btn btn-sm {{ in_array('6', $week_days) ? 'btn-primary' : 'btn-outline btn-primary' }}" />
                                <x-button :label="$dayNames['7']" type="button" wire:click="toggleWeekDay('7')"
                                    spinner="toggleWeekDay('7')"
                                    class="btn btn-sm {{ in_array('7', $week_days) ? 'btn-primary' : 'btn-outline btn-primary' }}" />
                            </div>

                            <!-- Display selected days -->
                            @if (!empty($week_days))
                                <div class="p-2 text-sm text-gray-600 bg-gray-50 rounded">
                                    <strong>روزهای انتخاب شده:</strong> {{ $this->formattedWeekDays }}
                                </div>
                            @endif

                            <!-- Show validation error -->
                            @error('week_days')
                                <span class="text-xs text-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.start_date')" :info="trans('course.page.runer.start_date_info')">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <x-datetime class="lg:min-w-60" wire:model.live.debounce.500ms="start_date" required
                                type="date" date-format="Y-m-d" inline :label="trans('validation.attributes.start_date')" />

                            <x-datetime class="lg:min-w-60" wire:model.live.debounce.500ms="end_date" required
                                x-bind:disabled="!$wire.start_date" type="date" date-format="Y-m-d" inline
                                :label="trans('validation.attributes.end_date')" />
                        </div>
                    </x-admin.shared.inline-input>
                    <x-admin.shared.inline-input :label="trans('validation.attributes.start_time')" :info="trans('course.page.runer.start_time_info')">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <x-datetime class="lg:min-w-60" wire:model="start_time" required type="time" inline
                                :label="trans('validation.attributes.start_time')" />
                        </div>
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.teacher_id')" :info="trans('course.page.runer.teacher_info')">
                        <x-choices-offline class="text-sm text-slate-600 lg:min-w-60" wire:model="teacher" required
                            placeholder="{{ trans('course.validations.select_a_teacher') }}" placeholder-value="0"
                            single clearable searchable :options="$teachers" option-value="value"
                            option-label="label" />
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.room_id')" :info="trans('course.page.runer.room_info')">
                        <x-select class="text-sm text-slate-600 lg:min-w-60" wire:model="room" required
                            placeholder="{{ trans('course.validations.select_a_room') }}" placeholder-value="0"
                            :options="$rooms" option-value="value" option-label="label" />
                    </x-admin.shared.inline-input>

                    @if (is_array($this->dates_example))
                        <div class="p-4 pb-2 text-xs tracking-wide opacity-60 divider divider-center">
                            {{ trans('course.page.runer.generated_sessions') }}
                        </div>
                        <ul class="list">

                            @foreach ($this->dates_example as $index => $date)
                                <li class="list-row">
                                    <div class="text-4xl font-thin tabular-nums opacity-30">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div class="list-col-grow">
                                        <div>{{ $date['date'] }}</div>
                                        <div class="text-xs font-semibold uppercase opacity-60">
                                            {{ $date['day_name'] }}
                                        </div>
                                    </div>
                                    <div class="text-xs font-semibold uppercase opacity-60">
                                        <div class="">{{ $date['start_time'] }}</div>
                                        <div class="">{{ $date['end_time'] }}</div>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    @elseif (is_string($this->dates_example) && !empty($this->dates_example))
                        <x-alert title="خطا در تولید جلسات" :description="$this->dates_example" icon="lucide.message-square-warning"
                            class="alert-warning" />
                    @endif

                </div>
            </x-step>
            <x-step step="3" text="جلسات دوره" class="!px-0">

                <div class="px-0 py-6 space-y-6 md:p-6">
                    <div class="mb-6">
                        <h2 class="mb-2 text-2xl font-bold text-slate-800">جلسات دوره</h2>
                        <p class="text-sm text-slate-600">لیست تمام جلسات این دوره آموزشی</p>
                    </div>

                    <div class="space-y-4">
                        @foreach ($sessions as $index => $session)
                            <div
                                class="p-3 bg-white rounded-lg border transition-shadow md:p-6 border-slate-200 hover:shadow-md">
                                <div class="flex items-start space-x-4">
                                    <!-- Session Number -->
                                    <div
                                        class="hidden justify-center items-center w-12 h-12 text-xl font-bold text-center text-white rounded-full md:flex bg-primary">
                                        {{ $session['order'] }}
                                    </div>

                                    <!-- Session Content -->
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 class="mb-1 text-lg font-semibold text-slate-800">
                                                    {{ $session['title'] ?? 'جلسه ' . ($index + 1) }}
                                                </h3>
                                                <p class="text-sm text-slate-600">
                                                    {{ $session['description'] }}
                                                </p>
                                            </div>

                                            <!-- Session Status Badge -->
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                                    some
                                                </span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-5">
                                            <x-input inline :label="trans('validation.attributes.date')" type="date"
                                                wire:model="sessions.{{ $index }}.date" />


                                            <x-input inline :label="trans('validation.attributes.start_time')" type="time"
                                                wire:model="sessions.{{ $index }}.start_time" />


                                            <x-input inline :label="trans('validation.attributes.end_time')" type="time"
                                                wire:model="sessions.{{ $index }}.end_time" />

                                            <x-select inline :label="trans('validation.attributes.type')" :options="SessionType::options()" option-value="value"
                                                option-label="label"
                                                wire:model.live="sessions.{{ $index }}.type" />

                                            <!-- Room Selection (for in-person and hybrid) -->
                                            @if ($session['type'] === 'in-person' || $session['type'] === 'hybrid')
                                                <x-select inline :label="trans('validation.attributes.room_id')" :options="$rooms"
                                                    option-value="value" option-label="label"
                                                    placeholder="{{ trans('course.validations.select_a_room') }}"
                                                    placeholder-value="0"
                                                    wire:model.live="sessions.{{ $index }}.room_id" />
                                            @endif

                                            <!-- Meeting Link (for online and hybrid) -->
                                            @if ($session['type'] === 'online' || $session['type'] === 'hybrid')
                                                <x-input inline :label="trans('validation.attributes.link')" type="url"
                                                    wire:model.live.debounce.500ms="sessions.{{ $index }}.link" />
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

            </x-step>
            <x-step step="4" text="خلاصه و تکمیل" class="!px-0">

                <div class="py-6 space-y-6">
                    <div class="mb-6">
                        <h2 class="mb-2 text-2xl font-bold text-slate-800">خلاصه دوره</h2>
                        <p class="text-sm text-slate-600">مرور نهایی اطلاعات دوره و آماده‌سازی برای اجرا</p>
                    </div>

                    <!-- Course Summary Card -->
                    <div class="p-6 bg-gradient-to-r to-blue-50 rounded-lg border from-primary/5 border-primary/20">
                        <div class="flex items-start space-x-4">
                            @if ($courseTemplate->getFirstMediaUrl('image'))
                                <img src="{{ $courseTemplate->getFirstMediaUrl('image') }}"
                                    alt="{{ $courseTemplate->title }}"
                                    class="hidden object-cover w-20 h-20 rounded-lg md:block">
                            @endif
                            <div class="flex-1">
                                <h3 class="hidden mb-2 text-xl font-bold md:block text-slate-800">
                                    {{ $courseTemplate->title }}</h3>
                                <p class="hidden mb-4 text-sm md:block text-slate-600">
                                    {{ $courseTemplate->description }}</p>

                                <!-- Course Stats -->
                                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                                    <div class="text-center">
                                        <div class="text-xl font-bold md:text-2xl text-primary">
                                            {{ $courseTemplate->sessionTemplates->count() }}</div>
                                        <div class="text-xs text-slate-500">جلسه</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xl font-bold md:text-2xl text-primary">
                                            {{ $courseTemplate->type->title() ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-500">نوع</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xl font-bold md:text-2xl text-primary">
                                            {{ $courseTemplate->level->title() ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-500">سطح</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xl font-bold md:text-2xl text-primary">
                                            {{ $courseTemplate->category->title ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-500">دسته‌بندی</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Checklist -->
                    <div class="p-6 bg-white rounded-lg border border-slate-200">
                        <h3 class="mb-4 text-lg font-semibold text-slate-800">چک‌لیست آماده‌سازی</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <x-icon name="o-check" class="w-5 h-5 text-green-500" />
                                <span class="text-sm text-slate-600">اطلاعات دوره تکمیل شده</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <x-icon name="o-check" class="w-5 h-5 text-green-500" />
                                <span class="text-sm text-slate-600">جلسات دوره تعریف شده</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <x-icon name="o-check" class="w-5 h-5 text-green-500" />
                                <span class="text-sm text-slate-600">دسته‌بندی و تگ‌ها تنظیم شده</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <x-icon name="o-check" class="w-5 h-5 text-green-500" />
                                <span class="text-sm text-slate-600">سطح و نوع دوره مشخص شده</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center pt-6 space-x-4">
                        <x-button label="شروع دوره" class="btn-success btn-lg" icon="o-play" spinner="startCourse"
                            wire:loading.attr="disabled" wire:target="startCourse" wire:click="startCourse" />
                    </div>
                </div>

            </x-step>
        </x-steps>

        <x-slot:actions separator class="flex w-full !items-center !justify-between">
            <x-button label="قبلی" class="btn-primary" wire:click="previousStep" wire:loading.attr="disabled"
                wire:target="nextStep, previousStep" spinner="previousStep" x-bind:disabled="$wire.runningStep <= 1"
                icon="lucide.arrow-right" />

            <x-button label="بعدی" class="btn-primary" wire:click="nextStep" wire:loading.attr="disabled"
                wire:target="nextStep, previousStep" spinner="nextStep" x-bind:disabled="$wire.runningStep >= 4"
                icon-right="lucide.arrow-left" />

        </x-slot:actions>
    </x-card>

</div>
