<div>
    {{-- Page Title --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-base-content">داشبورد تجارت الکترونیک</h1>
        <p class="text-sm text-base-content/60 mt-1">نمای کلی فروش و عملکرد شما</p>
    </div>

    {{-- Top Row: KPI Cards, Revenue Chart, Progress Chart, Congratulations --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
        {{-- Left Column: KPI Cards + Revenue Growth --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- KPI Cards Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Order KPI Card --}}
                <x-card class="bg-base-100 dark:bg-base-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-base-content/60 mb-1">سفارش</p>
                            <h3 class="text-3xl font-bold text-base-content">40</h3>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-error/10">
                            <x-icon name="o-shopping-cart" class="w-6 h-6 text-error" />
                        </div>
                    </div>
                </x-card>

                {{-- Purchase KPI Card --}}
                <x-card class="bg-base-100 dark:bg-base-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-base-content/60 mb-1">خرید</p>
                            <h3 class="text-3xl font-bold text-base-content">65</h3>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-success/10">
                            <x-icon name="o-tag" class="w-6 h-6 text-success" />
                        </div>
                    </div>
                </x-card>
            </div>

            {{-- Revenue Growth Chart Card --}}
            <x-card class="bg-base-100 dark:bg-base-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-base-content">رشد درآمد</h4>
                </div>
                <div class="mb-4">
                    <p class="text-3xl font-bold text-base-content">25,980</p>
                </div>
                {{-- Simple Bar Chart Representation --}}
                <div class="flex items-end gap-2 h-32">
                    @foreach ([40, 60, 45, 70, 55, 80, 65, 90, 75, 85] as $height)
                        <div class="flex-1 bg-primary/20 rounded-t" style="height: {{ $height }}%"></div>
                    @endforeach
                </div>
            </x-card>
        </div>

        {{-- Right Column: Progress Chart + Congratulations --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Circular Progress Card --}}
            <x-card class="bg-base-100 dark:bg-base-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-base-content">بازدیدهای 1401</h4>
                    <x-button class="btn-ghost btn-sm btn-square" icon="o-ellipsis-vertical" />
                </div>
                {{-- Circular Progress --}}
                <div class="flex flex-col items-center justify-center py-6">
                    <div class="relative w-40 h-40 mb-4">
                        <svg class="transform -rotate-90 w-40 h-40">
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="8"
                                fill="transparent" class="text-base-200" />
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="8"
                                fill="transparent" stroke-dasharray="439.8" stroke-dashoffset="87.96"
                                class="text-primary" />
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="8"
                                fill="transparent" stroke-dasharray="439.8" stroke-dashoffset="220"
                                class="text-error" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-bold text-base-content">80%</span>
                            <span class="text-xs text-base-content/60">بازدید کل</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 w-full">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-primary"></div>
                                <span class="text-base-content/70">هدف</span>
                            </div>
                            <span class="font-medium text-base-content">56%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-error"></div>
                                <span class="text-base-content/70">بازار</span>
                            </div>
                            <span class="font-medium text-base-content">26%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-warning"></div>
                                <span class="text-base-content/70">فروشگاه</span>
                            </div>
                            <span class="font-medium text-base-content">34%</span>
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Congratulations Card --}}
            <x-card class="bg-gradient-to-br from-primary to-primary-focus text-white shadow-lg">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-sm text-white/80 mb-1">تبریک می‌گوییم تونی!</p>
                        <p class="text-xs text-white/70">بهترین فروشنده ماه</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                        <x-icon name="o-trophy" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <h2 class="text-4xl font-bold mb-2">890 تومان</h2>
                <p class="text-sm text-white/80 mb-4">شما امروز 57% فروش بیشتری داشتید.</p>
                <x-button class="btn-sm btn-white text-primary">مشاهده فروش‌ها</x-button>
            </x-card>
        </div>
    </div>

    {{-- Middle Row: Latest Updates, Sales Overview, Weekly Order Summary --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
        {{-- Latest Updates Card --}}
        <div class="lg:col-span-4">
            <x-card class="bg-base-100 dark:bg-base-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-base-content">آخرین به‌روزرسانی</h4>
                    <x-select class="select-sm w-24" :options="[
                        ['label' => '1401', 'value' => '1401'],
                        ['label' => '1400', 'value' => '1400'],
                        ['label' => '1399', 'value' => '1399'],
                    ]" option-label="label" option-value="value" />
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-lg bg-base-50">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
                                <x-icon name="o-cube" class="w-5 h-5 text-primary" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-base-content">مجموع محصولات</p>
                                <p class="text-xs text-base-content/60">2K محصول جدید</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-base-content">10K</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-base-50">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-success/10">
                                <x-icon name="o-clock" class="w-5 h-5 text-success" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-base-content">کل فروش</p>
                                <p class="text-xs text-base-content/60">39K فروش جدید</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-base-content">26M</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-base-50">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-info/10">
                                <x-icon name="o-document-text" class="w-5 h-5 text-info" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-base-content">کل درآمد</p>
                                <p class="text-xs text-base-content/60">43K درآمد جدید</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-base-content">15M</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-base-50">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-warning/10">
                                <x-icon name="o-currency-dollar" class="w-5 h-5 text-warning" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-base-content">هزینه کل</p>
                                <p class="text-xs text-base-content/60">مجموع هزینه‌ها</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-base-content">2B</span>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Sales Overview Card --}}
        <div class="lg:col-span-4">
            <x-card class="bg-base-100 dark:bg-base-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-base-content">نمای کلی فروش‌ها</h4>
                    <x-button class="btn-ghost btn-sm btn-square" icon="o-ellipsis-vertical" />
                </div>
                <div class="mb-4">
                    <p class="text-sm text-base-content/60 mb-1">هفته قبل</p>
                    <p class="text-xs text-success">کارایی 45% بهتر نسبت به ماه قبل</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-base-content/70">درآمد این ماه</span>
                            <span class="text-sm font-semibold text-base-content">84,789 تومان</span>
                        </div>
                        <div class="w-full bg-base-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-base-content/70">میانگین فروش روزانه</span>
                            <span class="text-sm font-semibold text-base-content">12,398 تومان</span>
                        </div>
                        <div class="w-full bg-base-200 rounded-full h-2">
                            <div class="bg-success h-2 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Weekly Order Summary Card --}}
        <div class="lg:col-span-4">
            <x-card class="bg-base-100 dark:bg-base-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-base-content">خلاصه سفارش هفتگی</h4>
                    <x-button class="btn-ghost btn-sm btn-square" icon="o-ellipsis-vertical" />
                </div>
                {{-- Simple Line Chart Representation --}}
                <div class="h-48 flex items-end gap-1">
                    @foreach ([65, 75, 70, 80, 85, 90, 95, 88, 92, 98] as $height)
                        <div class="flex-1 bg-primary/30 rounded-t hover:bg-primary/50 transition-colors"
                            style="height: {{ $height }}%"></div>
                    @endforeach
                </div>
            </x-card>
        </div>
    </div>

    {{-- Bottom Row: Marketing Campaigns, Total Users --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Marketing Campaigns Card --}}
        <div class="lg:col-span-8">
            <x-card class="bg-base-100 dark:bg-base-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-base-content">کمپین‌های بازاریابی</h4>
                    <x-button class="btn-ghost btn-sm btn-square" icon="o-ellipsis-vertical" />
                </div>
                {{-- Summary Boxes --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-4 rounded-lg bg-base-50">
                        <p class="text-2xl font-bold text-base-content mb-1">25,768</p>
                        <p class="text-xs text-success mb-1">(+16.2%)</p>
                        <p class="text-xs text-base-content/60">12 فروردین 1401</p>
                    </div>
                    <div class="p-4 rounded-lg bg-base-50">
                        <p class="text-2xl font-bold text-base-content mb-1">5,352</p>
                        <p class="text-xs text-error mb-1">(-4.9%)</p>
                        <p class="text-xs text-base-content/60">12 فروردین 1401</p>
                    </div>
                </div>
                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>کمپین</th>
                                <th>رشد</th>
                                <th>عوارض</th>
                                <th>وضعیت</th>
                                <th>عمل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded bg-base-200 flex items-center justify-center text-xs font-bold">
                                            F</div>
                                        <span>لورم ایپسوم متن ساختگی</span>
                                    </div>
                                </td>
                                <td><span class="badge badge-success badge-sm">28.5%</span></td>
                                <td>1,324</td>
                                <td><span class="badge badge-success-outline badge-sm">فعال</span></td>
                                <td>
                                    <div class="flex gap-2">
                                        <x-button class="btn-ghost btn-xs" link="#">جزئیات</x-button>
                                        <x-button class="btn-ghost btn-xs" link="#">نقد و بررسی</x-button>
                                        <x-button class="btn-ghost btn-xs" link="#">صورتحساب</x-button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded bg-base-200 flex items-center justify-center text-xs font-bold">
                                            P</div>
                                        <span>لورم ایپسوم</span>
                                    </div>
                                </td>
                                <td><span class="badge badge-success badge-sm">56.6%</span></td>
                                <td>3,573</td>
                                <td><span class="badge badge-success-outline badge-sm">فعال</span></td>
                                <td>
                                    <div class="flex gap-2">
                                        <x-button class="btn-ghost btn-xs" link="#">جزئیات</x-button>
                                        <x-button class="btn-ghost btn-xs" link="#">نقد و بررسی</x-button>
                                        <x-button class="btn-ghost btn-xs" link="#">صورتحساب</x-button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded bg-base-200 flex items-center justify-center text-xs font-bold">
                                            N</div>
                                        <span>لورم ایپسوم متن</span>
                                    </div>
                                </td>
                                <td><span class="badge badge-error badge-sm">23.8%</span></td>
                                <td>12,347</td>
                                <td><span class="badge badge-error-outline badge-sm">تعطیل</span></td>
                                <td>
                                    <div class="flex gap-2">
                                        <x-button class="btn-ghost btn-xs" link="#">جزئیات</x-button>
                                        <x-button class="btn-ghost btn-xs" link="#">نقد و بررسی</x-button>
                                        <x-button class="btn-ghost btn-xs" link="#">صورتحساب</x-button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded bg-base-200 flex items-center justify-center text-xs font-bold">
                                            O</div>
                                        <span>لورم ایپسوم متن</span>
                                    </div>
                                </td>
                                <td><span class="badge badge-success badge-sm">81.4%</span></td>
                                <td>5,347</td>
                                <td><span class="badge badge-success-outline badge-sm">فعال</span></td>
                                <td>
                                    <div class="flex gap-2">
                                        <x-button class="btn-ghost btn-xs" link="#">جزئیات</x-button>
                                        <x-button class="btn-ghost btn-xs" link="#">نقد و بررسی</x-button>
                                        <x-button class="btn-ghost btn-xs" link="#">صورتحساب</x-button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded bg-base-200 flex items-center justify-center text-xs font-bold">
                                            G</div>
                                        <span>لورم ایپسوم متن</span>
                                    </div>
                                </td>
                                <td><span class="badge badge-error badge-sm">12.8%</span></td>
                                <td>45,678</td>
                                <td><span class="badge badge-error-outline badge-sm">تعطیل</span></td>
                                <td>
                                    <div class="flex gap-2">
                                        <x-button class="btn-ghost btn-xs" link="#">جزئیات</x-button>
                                        <x-button class="btn-ghost btn-xs" link="#">نقد و بررسی</x-button>
                                        <x-button class="btn-ghost btn-xs" link="#">صورتحساب</x-button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        {{-- Total Users Card --}}
        <div class="lg:col-span-4">
            <x-card class="bg-base-100 dark:bg-base-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-base-content">کل کاربران</h4>
                    <x-button class="btn-ghost btn-sm btn-square" icon="o-ellipsis-vertical" />
                </div>
                <div class="mb-6">
                    <h2 class="text-4xl font-bold text-base-content mb-2">8,634,820</h2>
                    <p class="text-sm text-base-content/60">فعالیت کنونی</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-base-content">ایران</span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-base-content/70">29.5k</span>
                                <span class="text-xs text-base-content/60">56%</span>
                            </div>
                        </div>
                        <div class="w-full bg-base-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: 56%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-base-content">فرانسه</span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-base-content/70">25.7k</span>
                                <span class="text-xs text-base-content/60">26%</span>
                            </div>
                        </div>
                        <div class="w-full bg-base-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: 26%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-base-content">ایتالیا</span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-base-content/70">11.5k</span>
                                <span class="text-xs text-base-content/60">34%</span>
                            </div>
                        </div>
                        <div class="w-full bg-base-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: 34%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-base-content">چین</span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-base-content/70">48.5k</span>
                                <span class="text-xs text-base-content/60">45%</span>
                            </div>
                        </div>
                        <div class="w-full bg-base-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-base-content">هندوستان</span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-base-content/70">22.1k</span>
                                <span class="text-xs text-base-content/60">7%</span>
                            </div>
                        </div>
                        <div class="w-full bg-base-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: 7%"></div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
