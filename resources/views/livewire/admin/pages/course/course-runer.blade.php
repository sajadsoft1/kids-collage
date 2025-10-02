<div class="h-full">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />


    <x-card>
        <x-steps wire:model="runningStep" stepper-classes="w-full p-5">
            <x-step step="1" text="اطلاعات دوره">

                <div class="p-6 space-y-6">
                    <!-- Course Header -->
                    <div class="flex items-start space-x-4">
                        @if ($courseTemplate->getFirstMediaUrl('image'))
                            <img src="{{ $courseTemplate->getFirstMediaUrl('image') }}" alt="{{ $courseTemplate->title }}"
                                class="object-cover w-24 h-24 rounded-lg">
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
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                    <span
                                        class="text-sm font-medium text-slate-800">{{ trans('validation.attributes.title') }}</span>
                                    <span class="text-sm text-slate-600">{{ $courseTemplate->title }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                    <span
                                        class="text-sm font-medium text-slate-800">{{ trans('validation.attributes.type') }}</span>
                                    <span
                                        class="text-sm text-slate-600">{{ $courseTemplate->type->title() ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                    <span
                                        class="text-sm font-medium text-slate-800">{{ trans('validation.attributes.level') }}</span>
                                    <span
                                        class="text-sm text-slate-600">{{ $courseTemplate->level->title() ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                    <span
                                        class="text-sm font-medium text-slate-800">{{ trans('validation.attributes.session_count') }}</span>
                                    <span
                                        class="text-sm text-slate-600">{{ $courseTemplate->sessionTemplates()->count() }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                    <span
                                        class="text-sm font-medium text-slate-800">{{ trans('validation.attributes.category') }}</span>
                                    <span
                                        class="text-sm text-slate-600">{{ $courseTemplate->category->title ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                    <span
                                        class="text-sm font-medium text-slate-800">{{ trans('validation.attributes.tags') }}</span>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($courseTemplate->tags as $tag)
                                            <span
                                                class="px-2 py-1 text-xs rounded-full bg-primary/10 text-primary">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
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
                    @endif

                    <!-- Prerequisites -->
                    @if ($courseTemplate->prerequisites && count($courseTemplate->prerequisites) > 0)
                        <section>
                            <h3 class="mb-4 text-xl font-bold leading-snug text-slate-800">پیش‌نیازها</h3>
                            <div class="space-y-2">
                                @foreach ($courseTemplate->prerequisites as $prerequisite)
                                    <div class="flex items-center space-x-2">
                                        <x-icon name="o-check" class="w-4 h-4 text-green-500" />
                                        <span class="text-sm text-slate-600">{{ $prerequisite }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>

            </x-step>
            <x-step step="2" text="جلسات دوره">

                <div class="p-6 space-y-6">
                    <div class="mb-6">
                        <h2 class="mb-2 text-2xl font-bold text-slate-800">جلسات دوره</h2>
                        <p class="text-sm text-slate-600">لیست تمام جلسات این دوره آموزشی</p>
                    </div>

                    @if ($courseTemplate->sessions && count($courseTemplate->sessions) > 0)
                        <div class="space-y-4">
                            @foreach ($courseTemplate->sessions as $index => $session)
                                <div
                                    class="p-6 bg-white rounded-lg border transition-shadow border-slate-200 hover:shadow-md">
                                    <div class="flex items-start space-x-4">
                                        <!-- Session Number -->
                                        <div
                                            class="flex justify-center items-center w-12 h-12 text-xl font-bold text-center text-white rounded-full bg-primary">
                                            {{ $session['order'] ?? $index + 1 }}
                                        </div>

                                        <!-- Session Content -->
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <h3 class="mb-1 text-lg font-semibold text-slate-800">
                                                        {{ $session['title'] ?? 'جلسه ' . ($index + 1) }}
                                                    </h3>
                                                    @if (isset($session['description']) && $session['description'])
                                                        <p class="text-sm text-slate-600">{{ $session['description'] }}
                                                        </p>
                                                    @endif
                                                </div>

                                                <!-- Session Status Badge -->
                                                <div class="flex items-center space-x-2">
                                                    <span
                                                        class="px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                                        {{ $session['type'] ?? 'عمومی' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Session Details -->
                                            <div class="flex items-center space-x-6 text-sm text-slate-500">
                                                @if (isset($session['duration_minutes']) && $session['duration_minutes'])
                                                    <div class="flex items-center space-x-1">
                                                        <x-icon name="lucide.timer" class="w-4 h-4" />
                                                        <span>{{ $session['duration_minutes'] }} دقیقه</span>
                                                    </div>
                                                @endif

                                                @if (isset($session['type']) && $session['type'])
                                                    <div class="flex items-center space-x-1">
                                                        <x-icon name="lucide.building-2" class="w-4 h-4" />
                                                        <span>{{ $session['type'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <x-icon name="o-document-text" class="mx-auto mb-4 w-16 h-16 text-slate-300" />
                            <h3 class="mb-2 text-lg font-medium text-slate-600">هیچ جلسه‌ای تعریف نشده</h3>
                            <p class="text-sm text-slate-500">برای این دوره هنوز جلسه‌ای تعریف نشده است.</p>
                        </div>
                    @endif
                </div>

            </x-step>
            <x-step step="3" text="خلاصه و تکمیل">

                <div class="p-6 space-y-6">
                    <div class="mb-6">
                        <h2 class="mb-2 text-2xl font-bold text-slate-800">خلاصه دوره</h2>
                        <p class="text-sm text-slate-600">مرور نهایی اطلاعات دوره و آماده‌سازی برای اجرا</p>
                    </div>

                    <!-- Course Summary Card -->
                    <div class="p-6 bg-gradient-to-r to-blue-50 rounded-lg border from-primary/5 border-primary/20">
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
                                            {{ $courseTemplate->sessions_count }}</div>
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
            <x-button label="بعدی" class="btn-primary" wire:if="$wire.runningStep < 3" wire:click="nextStep"
                wire:loading.attr="disabled" wire:target="nextStep, previousStep" spinner="nextStep" />
            <x-button label="قبلی" class="btn-primary" wire:if="$wire.runningStep > 1" wire:click="previousStep"
                wire:loading.attr="disabled" wire:target="nextStep, previousStep" spinner="previousStep" />
        </x-slot:actions>
    </x-card>

</div>
