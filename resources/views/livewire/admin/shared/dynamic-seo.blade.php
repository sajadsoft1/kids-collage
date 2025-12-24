@php
    use App\Models\Comment;
    use Illuminate\Support\Arr;
@endphp
@script
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endscript
<div class="">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <x-tabs wire:model="tabSelected" active-class="bg-primary rounded !text-white" label-class="px-4 py-2 font-semibold"
        label-div-class="p-2 mx-auto rounded bg-primary/5 w-fit">
        <x-tab name="config-tab" :label="trans('seo.config')">
            {{-- SEO Score & Readability --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @if (isset($seoScore))
                    <x-card shadow>
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <x-icon name="o-chart-bar" class="w-5 h-5 text-primary" />
                                    <h3 class="text-lg font-semibold">امتیاز سئو</h3>
                                </div>
                                <p class="text-sm text-gray-500">بر اساس معیارهای بهینه‌سازی</p>
                            </div>
                            <div class="text-center">
                                <div
                                    class="text-5xl font-bold {{ $seoScore['percentage'] >= 80 ? 'text-success' : ($seoScore['percentage'] >= 60 ? 'text-warning' : 'text-error') }}">
                                    {{ $seoScore['percentage'] }}%
                                </div>
                                <div class="text-sm text-gray-500 mt-1">{{ $seoScore['score'] }}/{{ $seoScore['maxScore'] }}
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2 pt-4 border-t">
                            @foreach ($seoScore['details'] as $key => $detail)
                                <div class="flex items-start gap-2 text-sm">
                                    @if ($detail['status'] === 'good')
                                        <x-icon name="o-check-circle" class="w-4 h-4 text-success mt-0.5 shrink-0" />
                                    @elseif($detail['status'] === 'warning')
                                        <x-icon name="o-exclamation-triangle" class="w-4 h-4 text-warning mt-0.5 shrink-0" />
                                    @elseif($detail['status'] === 'error')
                                        <x-icon name="o-x-circle" class="w-4 h-4 text-error mt-0.5 shrink-0" />
                                    @else
                                        <x-icon name="o-information-circle" class="w-4 h-4 text-info mt-0.5 shrink-0" />
                                    @endif
                                    <span class="flex-1">{{ $detail['message'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                @endif

                @if (isset($readabilityScore))
                    <x-card shadow>
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <x-icon name="o-book-open" class="w-5 h-5 text-primary" />
                                    <h3 class="text-lg font-semibold">{{ trans('seo.readability.title') }}</h3>
                                </div>
                                <p class="text-sm text-gray-500">Flesch Reading Ease</p>
                            </div>
                            <div class="text-center">
                                <div
                                    class="text-5xl font-bold {{ $readabilityScore['score'] >= 70 ? 'text-success' : ($readabilityScore['score'] >= 50 ? 'text-warning' : 'text-error') }}">
                                    {{ $readabilityScore['score'] }}
                                </div>
                                <div class="text-sm text-gray-500 mt-1">{{ $readabilityScore['level'] }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $readabilityScore['grade'] }}</div>
                            </div>
                        </div>
                        @if (isset($readabilityScore['stats']))
                            <div class="pt-4 border-t mb-4">
                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div class="text-center p-2 bg-base-200 rounded">
                                        <div class="font-semibold text-gray-700">{{ $readabilityScore['stats']['words'] }}</div>
                                        <div class="text-gray-500 mt-1">کلمات</div>
                                    </div>
                                    <div class="text-center p-2 bg-base-200 rounded">
                                        <div class="font-semibold text-gray-700">{{ $readabilityScore['stats']['sentences'] }}</div>
                                        <div class="text-gray-500 mt-1">جملات</div>
                                    </div>
                                    <div class="text-center p-2 bg-base-200 rounded">
                                        <div class="font-semibold text-gray-700">{{ $readabilityScore['stats']['avg_sentence_length'] }}</div>
                                        <div class="text-gray-500 mt-1">میانگین</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (isset($readabilityScore['suggestions']) && count($readabilityScore['suggestions']) > 0)
                            <div class="pt-4 border-t">
                                <h4 class="text-sm font-semibold mb-2 flex items-center gap-2">
                                    <x-icon name="o-light-bulb" class="w-4 h-4 text-warning" />
                                    پیشنهادات:
                                </h4>
                                <ul class="space-y-1.5 text-xs">
                                    @foreach ($readabilityScore['suggestions'] as $suggestion)
                                        <li class="flex items-start gap-2 text-gray-600">
                                            <span class="text-primary mt-0.5">•</span>
                                            <span>{{ $suggestion }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </x-card>
                @endif
            </div>

            {{-- Social Media Previews --}}
            <x-card title="پیش‌نمایش شبکه‌های اجتماعی" shadow class="mb-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    {{-- Google Preview --}}
                    <div class="p-4 border rounded-lg bg-base-100">
                        <div class="flex items-center gap-2 mb-3">
                            <x-icon name="o-magnifying-glass" class="w-4 h-4 text-primary" />
                            <p class="text-sm font-semibold text-gray-700">{{ trans('seo.social_preview.google') }}</p>
                        </div>
                        <h2 class="text-[#1a0dab] dark:text-base-content text-lg font-normal leading-tight mb-1">
                            {{ Str::limit($seo_title, 60) ?: 'عنوان صفحه شما اینجا نمایش داده می‌شود - نام سایت' }}
                        </h2>
                        <div class="text-[#006621] text-sm mb-1">
                            @if (method_exists($model, 'path'))
                                {{ $model->path() }}
                            @else
                                {{ urldecode(config('app.url') . '/' . \Illuminate\Support\Str::kebab($class) . '/' . $slug) }}
                            @endif
                        </div>
                        <p class="text-[#545454] text-sm line-clamp-2">
                            {{ Str::limit($seo_description, 160) ?: 'توضیحات متا اینجا نمایش داده می‌شود. اگر توضیحات خالی باشد، گوگل خودش متن دلخواهی از صفحه انتخاب می‌کند.' }}
                        </p>
                    </div>

                    {{-- Facebook Preview --}}
                    <div class="p-4 border rounded-lg bg-[#f2f3f5]">
                        <div class="flex items-center gap-2 mb-3">
                            <x-icon name="o-share" class="w-4 h-4 text-primary" />
                            <p class="text-sm font-semibold text-gray-700">{{ trans('seo.social_preview.facebook') }}</p>
                        </div>
                        @if ($og_image)
                            <img src="{{ $og_image }}" alt="{{ $seo_title }}"
                                class="w-full h-40 object-cover rounded mb-2" />
                        @else
                            <div
                                class="w-full h-40 bg-gray-200 rounded mb-2 flex items-center justify-center text-gray-400 text-xs">
                                <span>تصویر Open Graph</span>
                            </div>
                        @endif
                        <div class="bg-white p-3 rounded">
                            <div class="text-xs text-gray-500 mb-1">
                                @if (method_exists($model, 'path'))
                                    {{ parse_url($model->path(), PHP_URL_HOST) ?? parse_url(config('app.url'), PHP_URL_HOST) }}
                                @else
                                    {{ parse_url(config('app.url'), PHP_URL_HOST) }}
                                @endif
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1">
                                {{ Str::limit($seo_title, 60) }}
                            </h3>
                            <p class="text-xs text-gray-600 line-clamp-2">
                                {{ Str::limit($seo_description, 100) }}
                            </p>
                        </div>
                    </div>

                    {{-- Twitter Preview --}}
                    <div class="p-4 border rounded-lg bg-white">
                        <div class="flex items-center gap-2 mb-3">
                            <x-icon name="o-chat-bubble-left-right" class="w-4 h-4 text-primary" />
                            <p class="text-sm font-semibold text-gray-700">{{ trans('seo.social_preview.twitter') }}</p>
                        </div>
                        @if ($twitter_image)
                            <img src="{{ $twitter_image }}" alt="{{ $seo_title }}"
                                class="w-full h-40 object-cover rounded mb-2" />
                        @else
                            <div
                                class="w-full h-40 bg-gray-200 rounded mb-2 flex items-center justify-center text-gray-400 text-xs">
                                <span>تصویر Twitter Card</span>
                            </div>
                        @endif
                        <div class="bg-white p-3 rounded border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1">
                                {{ Str::limit($seo_title, 60) }}
                            </h3>
                            <p class="text-xs text-gray-600 line-clamp-2">
                                {{ Str::limit($seo_description, 100) }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>

            <form wire:submit="onSubmit">
                {{-- Basic SEO Fields --}}
                <x-card title="اطلاعات پایه سئو" shadow separator class="mb-4" progress-indicator="onSubmit">
                    <x-slot:menu>
                        <x-button :label="trans('general.reset')" icon="lucide.list-restart" wire:loading.attr="disabled"
                            wire:target="onSubmit" type="reset" />
                        <x-button :label="trans('general.submit')" icon="lucide.save" class="btn-primary" spinner="onSubmit"
                            type="submit" />
                    </x-slot:menu>

                    <div class="grid grid-cols-1 gap-4">
                        <x-input :label="trans('validation.attributes.slug')" wire:model.live.debounce="slug" icon="c-link" />
                        <div>
                            <x-input :label="trans('validation.attributes.seo_title')" wire:model.live.debounce="seo_title" class="w-full" />
                            <div class="text-xs mt-1 text-gray-500">
                                <span
                                    class="{{ strlen($seo_title ?? '') > 60 ? 'text-error' : (strlen($seo_title ?? '') < 50 ? 'text-warning' : 'text-success') }}">
                                    {{ strlen($seo_title ?? '') }}/60
                                </span>
                                <span class="text-gray-400 ml-2">({{ trans('seo.recommended') }}: 50-60)</span>
                            </div>
                        </div>
                        <div>
                            <x-textarea :label="trans('validation.attributes.seo_description')" wire:model.live.debounce="seo_description" />
                            <div class="text-xs mt-1 text-gray-500">
                                <span
                                    class="{{ strlen($seo_description ?? '') > 160 ? 'text-error' : (strlen($seo_description ?? '') < 150 ? 'text-warning' : 'text-success') }}">
                                    {{ strlen($seo_description ?? '') }}/160
                                </span>
                                <span class="text-gray-400 ml-2">({{ trans('seo.recommended') }}: 150-160)</span>
                            </div>
                        </div>
                        <x-input :label="trans('validation.attributes.canonical')" wire:model="canonical" icon-right="o-link" type="url" />
                        <x-radio inline :label="trans('validation.attributes.robots_meta')" :options="App\Enums\SeoRobotsMetaEnum::formatedCases()" wire:model="robots_meta"
                            option-value="value" option-label="label" option-hint="hint" required />
                    </div>
                </x-card>

                {{-- Advanced SEO Fields - Collapsible --}}
                <x-card shadow class="mb-4">
                    <div x-data="{ open: false }" class="w-full">
                        <button @click="open = !open" type="button"
                            class="w-full flex items-center justify-between p-4 text-left hover:bg-base-200 rounded-lg transition-colors">
                            <div class="flex items-center gap-3">
                                <x-icon name="o-sparkles" class="w-5 h-5 text-primary" />
                                <h3 class="text-lg font-semibold">فیلدهای پیشرفته سئو</h3>
                            </div>
                            <span class="transition-transform inline-block" :class="open ? 'rotate-180' : ''">
                                <x-icon name="o-chevron-down" class="w-5 h-5" />
                            </span>
                        </button>
                        <div x-show="open" x-collapse class="p-4 pt-0">
                            <div class="grid grid-cols-1 gap-4">
                                <x-input :label="trans('validation.attributes.og_image')" wire:model="og_image" icon-right="o-photo" type="url"
                                    hint="تصویر برای نمایش در Facebook و LinkedIn" />
                                <x-input :label="trans('validation.attributes.twitter_image')" wire:model="twitter_image" icon-right="o-photo" type="url"
                                    hint="تصویر برای نمایش در Twitter" />
                                <div>
                                    <x-input :label="trans('validation.attributes.focus_keyword')" wire:model.live.debounce="focus_keyword" icon-right="o-key"
                                        hint="کلمه کلیدی اصلی این صفحه" />
                                    @if (isset($keywordDensity) && !empty($focus_keyword))
                                        <div class="mt-2 p-3 bg-base-200 rounded-lg">
                                            <h4 class="text-sm font-semibold mb-2">تراکم کلمه کلیدی</h4>
                                            <div class="space-y-1 text-xs">
                                                <div class="flex justify-between">
                                                    <span>عنوان:</span>
                                                    <span
                                                        class="{{ $keywordDensity['title']['found'] ? 'text-success' : 'text-error' }}">
                                                        {{ $keywordDensity['title']['count'] }} بار
                                                        ({{ $keywordDensity['title']['density'] }}%)
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>توضیحات:</span>
                                                    <span
                                                        class="{{ $keywordDensity['description']['found'] ? 'text-success' : 'text-error' }}">
                                                        {{ $keywordDensity['description']['count'] }} بار
                                                        ({{ $keywordDensity['description']['density'] }}%)
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>محتوا:</span>
                                                    <span
                                                        class="{{ $keywordDensity['content']['found'] ? 'text-success' : 'text-error' }}">
                                                        {{ $keywordDensity['content']['count'] }} بار
                                                        ({{ $keywordDensity['content']['density'] }}%)
                                                    </span>
                                                </div>
                                                <div class="flex justify-between font-semibold pt-1 border-t">
                                                    <span>میانگین کلی:</span>
                                                    <span>{{ $keywordDensity['overall'] }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <x-textarea :label="trans('validation.attributes.meta_keywords')" wire:model="meta_keywords"
                                    hint="کلمات کلیدی اضافی (اختیاری، با کاما جدا کنید)" />
                                <x-input :label="trans('validation.attributes.author')" wire:model="author" icon-right="o-user"
                                    hint="نویسنده یا خالق محتوا" />
                            </div>
                        </div>
                    </div>
                </x-card>

                {{-- Redirect Settings --}}
                <x-card shadow class="mb-4">
                    <div x-data="{ open: false }" class="w-full">
                        <button @click="open = !open" type="button"
                            class="w-full flex items-center justify-between p-4 text-left hover:bg-base-200 rounded-lg transition-colors">
                            <div class="flex items-center gap-3">
                                <x-icon name="o-arrow-path" class="w-5 h-5 text-primary" />
                                <h3 class="text-lg font-semibold">تنظیمات ریدایرکت (301)</h3>
                            </div>
                            <span :class="open ? 'rotate-180' : ''" class="transition-transform inline-block">
                                <x-icon name="o-chevron-down" class="w-5 h-5" />
                            </span>
                        </button>
                        <div x-show="open" x-collapse class="p-4 pt-0">
                            <div class="grid grid-cols-1 gap-4">
                                <x-input :label="trans('validation.attributes.old_url')" wire:model="old_url" icon-right="o-link" type="url"
                                    hint="آدرس قدیمی که باید ریدایرکت شود" />
                                <x-input :label="trans('validation.attributes.redirect_to')" wire:model="redirect_to" icon-right="o-link" type="url"
                                    hint="آدرس جدید که باید به آن ریدایرکت شود" />
                            </div>
                        </div>
                    </div>
                </x-card>

                {{-- Sitemap Settings - Collapsible --}}
                <x-card shadow class="mb-4">
                    <div x-data="{ open: false }" class="w-full">
                        <button @click="open = !open" type="button"
                            class="w-full flex items-center justify-between p-4 text-left hover:bg-base-200 rounded-lg transition-colors">
                            <div class="flex items-center gap-3">
                                <x-icon name="o-map" class="w-5 h-5 text-primary" />
                                <h3 class="text-lg font-semibold">تنظیمات Sitemap</h3>
                            </div>
                            <span :class="open ? 'rotate-180' : ''" class="transition-transform inline-block">
                                <x-icon name="o-chevron-down" class="w-5 h-5" />
                            </span>
                        </button>
                        <div x-show="open" x-collapse class="p-4 pt-0">
                            <div class="grid grid-cols-1 gap-4">
                                <x-checkbox :label="trans('validation.attributes.sitemap_exclude')" wire:model="sitemap_exclude"
                                    hint="اگر فعال باشد، این صفحه از sitemap حذف می‌شود" />
                                <x-input :label="trans('validation.attributes.sitemap_priority')" wire:model="sitemap_priority" type="number" step="0.1"
                                    min="0" max="1" icon-right="o-star"
                                    hint="اولویت در sitemap (0.0 تا 1.0)" />
                                <x-select :label="trans('validation.attributes.sitemap_changefreq')" wire:model="sitemap_changefreq" :options="[
                                    ['id' => 'always', 'name' => 'Always'],
                                    ['id' => 'hourly', 'name' => 'Hourly'],
                                    ['id' => 'daily', 'name' => 'Daily'],
                                    ['id' => 'weekly', 'name' => 'Weekly'],
                                    ['id' => 'monthly', 'name' => 'Monthly'],
                                    ['id' => 'yearly', 'name' => 'Yearly'],
                                    ['id' => 'never', 'name' => 'Never'],
                                ]"
                                    option-value="id" option-label="name" hint="فرکانس تغییر محتوا" />
                            </div>
                        </div>
                    </div>
                </x-card>

                {{-- Image Optimization - Collapsible --}}
                <x-card shadow class="mb-4">
                    <div x-data="{ open: false }" class="w-full">
                        <button @click="open = !open" type="button"
                            class="w-full flex items-center justify-between p-4 text-left hover:bg-base-200 rounded-lg transition-colors">
                            <div class="flex items-center gap-3">
                                <x-icon name="o-photo" class="w-5 h-5 text-primary" />
                                <h3 class="text-lg font-semibold">بهینه‌سازی تصویر</h3>
                            </div>
                            <span :class="open ? 'rotate-180' : ''" class="transition-transform inline-block">
                                <x-icon name="o-chevron-down" class="w-5 h-5" />
                            </span>
                        </button>
                        <div x-show="open" x-collapse class="p-4 pt-0">
                            <div class="grid grid-cols-1 gap-4">
                                <x-input :label="trans('validation.attributes.image_alt')" wire:model="image_alt" icon-right="o-photo"
                                    hint="متن جایگزین تصویر (برای SEO و دسترسی‌پذیری)" />
                                <x-input :label="trans('validation.attributes.image_title')" wire:model="image_title" icon-right="o-photo"
                                    hint="عنوان تصویر (نمایش داده می‌شود هنگام hover)" />
                            </div>
                        </div>
                    </div>
                </x-card>
            </form>
        </x-tab>
        <x-tab name="report-tab" :label="trans('seo.reports')">
            <div class="grid grid-cols-4 gap-2">
                <x-stat :title="trans('seo.stats.views')" :value="$viewsCount['all']" icon="lucide.view" />

                <x-stat :title="trans('seo.stats.comments')" :value="$commentsCount['all']" icon="lucide.message-circle" />

                <x-stat :title="trans('seo.stats.wishes')" :value="$wishesCount['all']" icon="lucide.save" />
            </div>
            <div class="grid grid-cols-2 gap-4 mt-5">
                <x-card :title="trans('seo.charts.views')" :subtitle="trans('seo.from_date_to_date', [
                    'from' => Arr::first($viewsChart['data']['labels'] ?? []),
                    'to' => Arr::last($viewsChart['data']['labels'] ?? []),
                ])">
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label"
                            wire:model.live="viewsChartSelectedMonth" />
                    </x-slot:menu>
                    <div wire:loading.delay wire:target="viewsChartSelectedMonth"
                        class="flex items-center justify-center h-64">
                        <span class="loading loading-spinner loading-lg"></span>
                    </div>
                    <div wire:loading.remove.delay wire:target="viewsChartSelectedMonth">
                        <x-chart wire:model="viewsChart" id="viewsChartId" wire:key="viewsChartKey" />
                    </div>
                </x-card>

                <x-card :title="trans('seo.charts.comments')" :subtitle="trans('seo.from_date_to_date', [
                    'from' => Arr::first($commentsChart['data']['labels'] ?? []),
                    'to' => Arr::last($commentsChart['data']['labels'] ?? []),
                ])">
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label"
                            wire:model.live="commentsChartSelectedMonth" />
                    </x-slot:menu>
                    <div wire:loading.delay wire:target="commentsChartSelectedMonth"
                        class="flex items-center justify-center h-64">
                        <span class="loading loading-spinner loading-lg"></span>
                    </div>
                    <div wire:loading.remove.delay wire:target="commentsChartSelectedMonth">
                        <x-chart wire:model="commentsChart" id="commentsChartId" wire:key="commentsChartKey" />
                    </div>
                </x-card>

                <x-card :title="trans('seo.charts.wishes')" :subtitle="trans('seo.from_date_to_date', [
                    'from' => Arr::first($wishesChart['data']['labels'] ?? []),
                    'to' => Arr::last($wishesChart['data']['labels'] ?? []),
                ])">
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label"
                            wire:model.live="wishesChartSelectedMonth" />
                    </x-slot:menu>
                    <div wire:loading.delay wire:target="wishesChartSelectedMonth"
                        class="flex items-center justify-center h-64">
                        <span class="loading loading-spinner loading-lg"></span>
                    </div>
                    <div wire:loading.remove.delay wire:target="wishesChartSelectedMonth">
                        <x-chart wire:model="wishesChart" id="wishesChartId" wire:key="wishesChartKey" />
                    </div>
                </x-card>

            </div>

        </x-tab>

        <x-tab name="view-tab" :label="trans('seo.views')">
            <div class="grid md:grid-cols-2 gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <x-admin.shared.stat :title="trans('seo.stats_report.1')" :value="$viewsCount[1]" :icon="$viewsCount[3] > $viewsCount[1] ? 'o-arrow-trending-up' : 'o-arrow-trending-down'" />
                    <x-admin.shared.stat :title="trans('seo.stats_report.3')" :value="$viewsCount[3]" :icon="$viewsCount[6] > $viewsCount[3] ? 'o-arrow-trending-up' : 'o-arrow-trending-down'" />
                    <x-admin.shared.stat :title="trans('seo.stats_report.6')" :value="$viewsCount[6]" :icon="$viewsCount[12] > $viewsCount[6] ? 'o-arrow-trending-up' : 'o-arrow-trending-down'" />
                    <x-admin.shared.stat :title="trans('seo.stats_report.12')" :value="$viewsCount[12]" :icon="$viewsCount[12] > 0 ? 'o-arrow-trending-up' : 'o-arrow-trending-down'" />
                </div>
                <x-card :title="trans('seo.charts.views')" :subtitle="trans('seo.from_date_to_date', [
                    'from' => Arr::first($viewsChart['data']['labels'] ?? []),
                    'to' => Arr::last($viewsChart['data']['labels'] ?? []),
                ])">
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label"
                            wire:model.live="viewsChartSelectedMonth" />
                    </x-slot:menu>
                    <div wire:loading.delay wire:target="viewsChartSelectedMonth"
                        class="flex items-center justify-center h-64">
                        <span class="loading loading-spinner loading-lg"></span>
                    </div>
                    <div wire:loading.remove.delay wire:target="viewsChartSelectedMonth">
                        <x-chart wire:model="viewsChart" id="viewsChartId" wire:key="viewsChartKey" />
                    </div>
                </x-card>
            </div>


            <x-card title="Views" class="flex-1 mt-5">
                <x-table :headers="[
                    ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
                    ['key' => 'user.full_name', 'label' => trans('datatable.user_name')],
                    [
                        'key' => 'created_at',
                        'label' => trans('datatable.created_at'),
                        'format' => ['date', 'Y/m/d H:i'],
                    ],
                    ['key' => 'ip', 'label' => trans('validation.attributes.ip_address')],
                ]" :rows="$views" show-empty-text :empty-text="trans('seo.charts.empty')" with-pagination />
            </x-card>
        </x-tab>

        <x-tab name="wish-tab" :label="trans('seo.wished')">
            <div class="grid md:grid-cols-2 gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <x-stat :title="trans('seo.stats_report.1')" :value="$wishesCount[1]" :icon="$wishesCount[3] > $wishesCount[1] ? 'o-arrow-trending-up' : 'o-arrow-trending-down'" />
                    <x-stat :title="trans('seo.stats_report.3')" :value="$wishesCount[3]" :icon="$wishesCount[6] > $wishesCount[3] ? 'o-arrow-trending-up' : 'o-arrow-trending-down'" />
                    <x-stat :title="trans('seo.stats_report.6')" :value="$wishesCount[6]" :icon="$wishesCount[12] > $wishesCount[6] ? 'o-arrow-trending-up' : 'o-arrow-trending-down'" />
                    <x-stat :title="trans('seo.stats_report.12')" :value="$wishesCount[12]" :icon="$wishesCount[12] > 0 ? 'o-arrow-trending-up' : 'o-arrow-trending-down'" />
                </div>
                <x-card :title="trans('seo.charts.wishes')" :subtitle="trans('seo.from_date_to_date', [
                    'from' => Arr::first($wishesChart['data']['labels'] ?? []),
                    'to' => Arr::last($wishesChart['data']['labels'] ?? []),
                ])">
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label"
                            wire:model.live="wishesChartSelectedMonth" />
                    </x-slot:menu>
                    <div wire:loading.delay wire:target="wishesChartSelectedMonth"
                        class="flex items-center justify-center h-64">
                        <span class="loading loading-spinner loading-lg"></span>
                    </div>
                    <div wire:loading.remove.delay wire:target="wishesChartSelectedMonth">
                        <x-chart wire:model="wishesChart" id="wishesChartId" wire:key="wishesChartKey" />
                    </div>
                </x-card>
            </div>


            <x-card title="Wished" class="flex-1 mt-5">
                <x-table :headers="[
                    ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
                    ['key' => 'user.full_name', 'label' => trans('datatable.user_name')],
                    [
                        'key' => 'created_at',
                        'label' => trans('datatable.created_at'),
                        'format' => ['date', 'Y/m/d H:i'],
                    ],
                ]" :rows="$wishes" show-empty-text :empty-text="trans('seo.charts.empty')" with-pagination />
            </x-card>
        </x-tab>
        <x-tab name="comments-tab" :label="trans('seo.comments')">
            <div class="grid md:grid-cols-2 gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <x-stat :title="trans('seo.stats_report.1')" :description="trans('seo.from_date_to_date', [
                        'from' => $dates[0]['start']->format('Y-m-d'),
                        'to' => $dates[0]['end']->format('Y-m-d'),
                    ])" :value="$commentsCount[1]" :color="$commentsCount[3] > $commentsCount[1] ? 'text-success' : 'text-secondary'"
                        :icon="$commentsCount[3] > $commentsCount[1]
                            ? 'o-arrow-trending-up'
                            : 'o-arrow-trending-down'" />
                    <x-stat :title="trans('seo.stats_report.3')" :description="trans('seo.from_date_to_date', [
                        'from' => $dates[1]['start']->format('Y-m-d'),
                        'to' => $dates[1]['end']->format('Y-m-d'),
                    ])" :value="$commentsCount[3]" :color="$commentsCount[6] > $commentsCount[3] ? 'text-success' : 'text-secondary'"
                        :icon="$commentsCount[6] > $commentsCount[3]
                            ? 'o-arrow-trending-up'
                            : 'o-arrow-trending-down'" />
                    <x-stat :title="trans('seo.stats_report.6')" :description="trans('seo.from_date_to_date', [
                        'from' => $dates[2]['start']->format('Y-m-d'),
                        'to' => $dates[2]['end']->format('Y-m-d'),
                    ])" :value="$commentsCount[6]" :color="$commentsCount[12] > $commentsCount[6] ? 'text-success' : 'text-secondary'"
                        :icon="$commentsCount[12] > $commentsCount[6]
                            ? 'o-arrow-trending-up'
                            : 'o-arrow-trending-down'" />
                    <x-stat :title="trans('seo.stats_report.12')" :description="trans('seo.from_date_to_date', [
                        'from' => $dates[3]['start']->format('Y-m-d'),
                        'to' => $dates[3]['end']->format('Y-m-d'),
                    ])" :value="$commentsCount[12]" :color="$commentsCount[12] > 0 ? 'text-success' : 'text-secondary'"
                        :icon="$commentsCount[12] > 0 ? 'o-arrow-trending-up' : 'o-arrow-trending-down'" />
                    <div class=""></div>
                    <div class=""></div>
                </div>
                <x-card :title="trans('seo.charts.comments')" :subtitle="trans('seo.from_date_to_date', [
                    'from' => Arr::first($commentsChart['data']['labels'] ?? []),
                    'to' => Arr::last($commentsChart['data']['labels'] ?? []),
                ])">
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label"
                            wire:model.live="commentsChartSelectedMonth" />
                    </x-slot:menu>
                    <div wire:loading.delay wire:target="commentsChartSelectedMonth"
                        class="flex items-center justify-center h-64">
                        <span class="loading loading-spinner loading-lg"></span>
                    </div>
                    <div wire:loading.remove.delay wire:target="commentsChartSelectedMonth">
                        <x-chart wire:model="commentsChart" id="commentsChartId" wire:key="commentsChartKey" />
                    </div>
                </x-card>
            </div>


            <x-card title="Comments" class="flex-1 mt-5">
                <x-table :headers="[
                    ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
                    ['key' => 'user.full_name', 'label' => trans('datatable.user_name')],
                    ['key' => 'comment', 'label' => trans('validation.attributes.comment')],
                    ['key' => 'admin_note', 'label' => trans('validation.attributes.admin_note')],
                    [
                        'key' => 'published',
                        'label' => trans('validation.attributes.published'),
                        'format' => fn($row, $field) => $field ? trans('core.published') : trans('core.un_published'),
                    ],
                    ['key' => 'suggest', 'label' => trans('validation.attributes.suggest')],
                    ['key' => 'rate', 'label' => trans('validation.attributes.rate')],
                ]" :rows="$comments" :cell-decoration="[
                    'published' => [
                        'bg-warning underline' => fn(Comment $comment) => $comment->published === 0,
                        'bg-success' => fn(Comment $comment) => $comment->published === 1,
                    ],
                ]" show-empty-text :empty-text="trans('seo.charts.empty')"
                    expandable wire:model="expandedComments" with-pagination>
                    @scope('expansion', $row)
                        <div class="bg-base-200 p-8 font-bold">
                            <strong>{{ trans('validation.attributes.answer') }}:</strong>
                            <br>
                            {{ $row->admin_note ?? trans('comment.no_admin_answer') }}
                        </div>
                    @endscope
                </x-table>
            </x-card>

        </x-tab>

        <x-tab name="linking-tab" :label="trans('seo.linking.title')">
            <x-card :title="trans('seo.linking.title')" subtitle="پیشنهادات لینک‌دهی داخلی برای بهبود SEO">
                @if (isset($internalLinks) && count($internalLinks) > 0)
                    <div class="space-y-3">
                        @foreach ($internalLinks as $link)
                            <div class="p-4 border rounded-lg bg-base-100">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <a href="{{ $link['url'] }}" target="_blank"
                                            class="text-primary hover:underline font-semibold">
                                            {{ $link['anchor_text'] }}
                                        </a>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $link['model']->title ?? 'No title' }}</p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="badge badge-sm">{{ $link['reason'] }}</span>
                                            <span class="text-xs text-gray-400">
                                                امتیاز: {{ number_format($link['relevance_score'] * 100, 1) }}%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $link['url'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        {{ trans('seo.linking.no_suggestions') }}
                    </div>
                @endif
            </x-card>
        </x-tab>
    </x-tabs>

</div>
