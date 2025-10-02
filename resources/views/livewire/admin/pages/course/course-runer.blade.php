@php
    use App\Helpers\Constants;
    use App\Enums\CourseStatusEnum;
@endphp
<div class="h-full">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />


    <x-card>
        <x-steps wire:model="runningStep" stepper-classes="w-full p-5">
            <x-step step="1" text="اطلاعات دوره">

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
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @foreach ($informations as $information)
                                <div class="flex items-center justify-between py-2 border-b border-slate-200">
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
                            @forelse ($courseTemplate->prerequisites as $prerequisite)
                                <div class="flex items-center space-x-2">
                                    <x-icon name="o-check" class="w-4 h-4 text-green-500" />
                                    <span class="text-sm text-slate-600">{{ $prerequisite }}</span>
                                </div>
                            @empty
                                <div class="flex items-center space-x-2">
                                    <x-icon name="o-user" class="w-4 h-4 text-red-500" />
                                    <span class="text-sm text-slate-600">هیچ پیش‌نیازی تعریف نشده</span>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <!-- Course Description -->
                    @if ($courseTemplate->body)
                        <section>
                            <h3 class="mb-4 text-xl font-bold leading-snug text-slate-800">
                                {{ trans('validation.attributes.description') }}</h3>
                            <div class="prose max-w-none">
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
            <x-step step="2" text="اطلاعات اجرایی">

                <div class="p-6 space-y-6">

                    <x-admin.shared.inline-input :label="trans('validation.attributes.capacity')" :info="trans('course.page.runer.capacity_info')">
                        <x-input class="text-sm text-slate-600 lg:min-w-60" wire:model="capacity" required
                            min="1" />
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.price')" :info="trans('course.page.runer.price_info')">
                        <x-input class="text-sm text-slate-600 lg:min-w-60" wire:model="price" required
                            min="0" />
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.status')" :info="trans('course.page.runer.status_info')">
                        <x-select class="text-sm text-slate-600 lg:min-w-60" wire:model="status" required
                            :options="CourseStatusEnum::options()" option-key="value" option-label="label" />
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.term_id')" :info="trans('course.page.runer.term_info')">
                        <x-select class="text-sm text-slate-600 lg:min-w-60" wire:model="term" required
                            :options="$terms" option-key="value" option-label="label" />
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.start_date')" :info="trans('course.page.runer.start_date_info')">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <x-input class="text-sm text-slate-600 lg:min-w-60" wire:model="start_date" required
                                type="date" />
                            <x-input class="text-sm text-slate-600 lg:min-w-60" wire:model="end_date" required
                                type="date" />
                        </div>
                    </x-admin.shared.inline-input>
                    <x-admin.shared.inline-input :label="trans('validation.attributes.start_time')" :info="trans('course.page.runer.start_time_info')">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <x-input class="text-sm text-slate-600 lg:min-w-60" wire:model="start_time" required
                                type="time" />
                            <x-input class="text-sm text-slate-600 lg:min-w-60" wire:model="end_time" required
                                type="time" />
                        </div>
                    </x-admin.shared.inline-input>


                    <x-admin.shared.inline-input :label="trans('validation.attributes.week_days')" :info="trans('course.page.runer.week_days_info')">
                        <div class="space-y-3">
                            <!-- Quick action buttons -->
                            <div class="flex gap-2 mb-3">
                                <x-button label="انتخاب همه" type="button" wire:click="selectAllWeekDays"
                                    spinner="selectAllWeekDays" class="btn btn-sm btn-outline btn-primary" />
                                <x-button label="پاک کردن همه" type="button" wire:click="clearWeekDays"
                                    spinner="clearWeekDays" class="btn btn-sm btn-outline btn-secondary" />
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
                                <div class="p-2 text-sm text-gray-600 rounded bg-gray-50">
                                    <strong>روزهای انتخاب شده:</strong> {{ $this->formattedWeekDays }}
                                </div>
                            @endif

                            <!-- Show validation error -->
                            @error('week_days')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.teacher_id')" :info="trans('course.page.runer.teacher_info')">
                        <x-select class="text-sm text-slate-600 lg:min-w-60" wire:model="teacher" required
                            :options="$teachers" option-key="value" option-label="label" />
                    </x-admin.shared.inline-input>

                    <x-admin.shared.inline-input :label="trans('validation.attributes.room_id')" :info="trans('course.page.runer.room_info')">
                        <x-select class="text-sm text-slate-600 lg:min-w-60" wire:model="room" required
                            :options="$rooms" option-key="value" option-label="label" />
                    </x-admin.shared.inline-input>

                </div>
            </x-step>
            <x-step step="3" text="جلسات دوره">

                <div class="p-6 space-y-6">
                    <div class="mb-6">
                        <h2 class="mb-2 text-2xl font-bold text-slate-800">جلسات دوره</h2>
                        <p class="text-sm text-slate-600">لیست تمام جلسات این دوره آموزشی</p>
                    </div>

                    @if ($courseTemplate->sessionTemplates && count($courseTemplate->sessionTemplates) > 0)
                        <div class="space-y-4">
                            @foreach ($courseTemplate->sessionTemplates as $index => $sessionTemplate)
                                <div
                                    class="p-6 transition-shadow bg-white border rounded-lg border-slate-200 hover:shadow-md">
                                    <div class="flex items-start space-x-4">
                                        <!-- Session Number -->
                                        <div
                                            class="flex items-center justify-center w-12 h-12 text-xl font-bold text-center text-white rounded-full bg-primary">
                                            {{ $sessionTemplate->order }}
                                        </div>

                                        <!-- Session Content -->
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-3">
                                                <div>
                                                    <h3 class="mb-1 text-lg font-semibold text-slate-800">
                                                        {{ $sessionTemplate->title ?? 'جلسه ' . ($index + 1) }}
                                                    </h3>
                                                    <p class="text-sm text-slate-600">
                                                        {{ $sessionTemplate->description }}
                                                    </p>
                                                </div>

                                                <!-- Session Status Badge -->
                                                <div class="flex items-center space-x-2">
                                                    <span
                                                        class="px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                                        {{ $sessionTemplate->type->title() }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Session Details -->
                                            <div class="flex items-center space-x-6 text-sm text-slate-500">
                                                <div class="flex items-center space-x-1">
                                                    <x-icon name="lucide.timer" class="w-4 h-4" />
                                                    <span>{{ $sessionTemplate->duration_minutes }} دقیقه</span>
                                                </div>

                                                <div class="flex items-center space-x-1">
                                                    <x-icon name="lucide.building-2" class="w-4 h-4" />
                                                    <span>{{ $sessionTemplate->type->title() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <x-icon name="o-document-text" class="w-16 h-16 mx-auto mb-4 text-slate-300" />
                            <h3 class="mb-2 text-lg font-medium text-slate-600">هیچ جلسه‌ای تعریف نشده</h3>
                            <p class="text-sm text-slate-500">برای این دوره هنوز جلسه‌ای تعریف نشده است.</p>
                        </div>
                    @endif
                </div>

            </x-step>
            <x-step step="4" text="خلاصه و تکمیل">

                <div class="p-6 space-y-6">
                    <div class="mb-6">
                        <h2 class="mb-2 text-2xl font-bold text-slate-800">خلاصه دوره</h2>
                        <p class="text-sm text-slate-600">مرور نهایی اطلاعات دوره و آماده‌سازی برای اجرا</p>
                    </div>

                    <!-- Course Summary Card -->
                    <div class="p-6 border rounded-lg bg-gradient-to-r to-blue-50 from-primary/5 border-primary/20">
                        <div class="flex items-start space-x-4">
                            @if ($courseTemplate->getFirstMediaUrl('image'))
                                <img src="{{ $courseTemplate->getFirstMediaUrl('image') }}"
                                    alt="{{ $courseTemplate->title }}" class="object-cover w-20 h-20 rounded-lg">
                            @endif
                            <div class="flex-1">
                                <h3 class="mb-2 text-xl font-bold text-slate-800">{{ $courseTemplate->title }}</h3>
                                <p class="mb-4 text-sm text-slate-600">{{ $courseTemplate->description }}</p>

                                <!-- Course Stats -->
                                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-primary">
                                            {{ $courseTemplate->sessionTemplates->count() }}</div>
                                        <div class="text-xs text-slate-500">جلسه</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-primary">
                                            {{ $courseTemplate->type->title ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-500">نوع</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-primary">
                                            {{ $courseTemplate->level->title ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-500">سطح</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-primary">
                                            {{ $courseTemplate->category->title ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-500">دسته‌بندی</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Checklist -->
                    <div class="p-6 bg-white border rounded-lg border-slate-200">
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
                        <x-button label="شروع دوره" class="btn-primary btn-lg" icon="o-play"
                            wire:click="startCourse" />
                        <x-button label="ویرایش دوره" class="btn-outline btn-lg" icon="o-pencil"
                            wire:click="editCourse" />
                        <x-button label="پیش‌نمایش" class="btn-outline btn-lg" icon="o-eye"
                            wire:click="previewCourse" />
                    </div>
                </div>

            </x-step>
        </x-steps>

        <x-slot:actions separator>
            <x-button label="قبلی" class="btn-primary" wire:if="$wire.runningStep > 1" wire:click="previousStep"
                wire:loading.attr="disabled" wire:target="nextStep, previousStep" spinner="previousStep" />

            <x-button label="بعدی" class="btn-primary" wire:if="$wire.runningStep < 3" wire:click="nextStep"
                wire:loading.attr="disabled" wire:target="nextStep, previousStep" spinner="nextStep" />

        </x-slot:actions>
    </x-card>

</div>
