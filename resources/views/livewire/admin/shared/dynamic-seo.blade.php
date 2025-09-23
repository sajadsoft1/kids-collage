@script
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endscript
<div class="">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <x-tabs
            wire:model="tabSelected"
            active-class="bg-primary rounded !text-white p-2"
            label-class="font-semibold"
            label-div-class="bg-primary/5 rounded w-fit p-2"
    >
        <x-tab name="config-tab" :label="trans('seo.config')">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="my-6 p-4 border rounded-lg bg-white shadow-sm">
                    <p class="text-sm text-gray-500 mb-2">پیش‌نمایش در نتایج گوگل</p>

                    {{-- Title --}}
                    <h2 class="text-[#1a0dab] text-xl font-normal truncate">
                        {{ Str::limit($seo_title,60) ?: 'عنوان صفحه شما اینجا نمایش داده می‌شود - نام سایت' }}
                    </h2>

                    {{-- Url --}}
                    <div class="text-[#006621] text-sm">
                        {{ urldecode(localized_route($class.'.detail', [$class => $slug])) }}
                    </div>

                    {{-- Description --}}
                    <p class="text-[#545454] text-sm mt-1 line-clamp-2">
                        {{ Str::limit($seo_description,160) ?: 'توضیحات متا اینجا نمایش داده می‌شود. اگر توضیحات خالی باشد، گوگل خودش متن دلخواهی از صفحه انتخاب می‌کند.' }}
                    </p>
                </div>
            </div>

            <form wire:submit="onSubmit">
                <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="onSubmit">
                    <x-slot:menu>
                        <x-button :label="trans('general.reset')" icon="lucide.list-restart" wire:loading.attr="disabled" wire:target="onSubmit" type="reset"/>
                        <x-button :label="trans('general.submit')" icon="lucide.save" class="btn-primary" spinner="onSubmit" type="submit"/>
                    </x-slot:menu>

                    <div class="grid grid-cols-1 gap-4">
                        <x-input :label="trans('validation.attributes.slug')"
                                 wire:model.live="slug"
                        />
                        <x-input :label="trans('validation.attributes.seo_title')"
                                 wire:model.live="seo_title"
                                 class="w-full"
                        />
                        <x-textarea :label="trans('validation.attributes.seo_description')"
                                    wire:model.live="seo_description"
                        />
                        <x-input :label="trans('validation.attributes.canonical')"
                                 wire:model="canonical"
                                 type="url"
                        />
                        <x-input :label="trans('validation.attributes.old_url')"
                                 wire:model="old_url"
                                 type="url"
                        />
                        <x-input :label="trans('validation.attributes.redirect_to')"
                                 wire:model="redirect_to"
                                 type="url"
                        />

                        <x-select :label="trans('validation.attributes.robots_meta')"
                                  wire:model="robots_meta"
                                  :options="App\Enums\SeoRobotsMetaEnum::formatedCases()"
                                  option-label="label"
                                  option-value="value"
                                  required
                        />
                    </div>
                </x-card>
            </form>
        </x-tab>
        <x-tab name="report-tab" :label="trans('seo.reports')">
            <div class="grid grid-cols-4 gap-2">
                <x-stat
                        :title="trans('seo.stats.views')"
                        :value="$viewsCount"
                        icon="lucide.view"/>

                <x-stat
                        :title="trans('seo.stats.likes')"
                        :value="$likesCount"
                        icon="lucide.heart"/>

                <x-stat
                        :title="trans('seo.stats.comments')"
                        :value="$commentsCount"
                        icon="lucide.message-circle"/>

                <x-stat
                        :title="trans('seo.stats.wishes')"
                        :value="$wishesCount"
                        icon="lucide.save"/>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-5">
                <x-card
                        :title="trans('seo.charts.views')"
                        :subtitle="trans('seo.from_date_to_date', ['from' => array_first($viewsChart['data']['labels']), 'to' => array_last($viewsChart['data']['labels'])])"
                        >
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label" wire:model.live="viewsChartSelectedMonth"/>
                    </x-slot:menu>
                    <x-chart wire:model="viewsChart" id="viewsChartId" wire:key="viewsChartKey"/>
                </x-card>

                <x-card
                        :title="trans('seo.charts.comments')"
                        :subtitle="trans('seo.from_date_to_date', ['from' => array_first($commentsChart['data']['labels']), 'to' => array_last($commentsChart['data']['labels'])])"
                        >
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label" wire:model.live="commentsChartSelectedMonth"/>
                    </x-slot:menu>
                    <x-chart wire:model="commentsChart" id="commentsChartId" wire:key="commentsChartKey"/>
                </x-card>

                <x-card
                        :title="trans('seo.charts.likes')"
                        :subtitle="trans('seo.from_date_to_date', ['from' => array_first($likesChart['data']['labels']), 'to' => array_last($likesChart['data']['labels'])])"
                >
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label" wire:model.live="likesChartSelectedMonth"/>
                    </x-slot:menu>
                    <x-chart wire:model="likesChart" id="likesChartId" wire:key="likesChartKey"/>
                </x-card>

            </div>

        </x-tab>

        <x-tab name="comments-tab" :label="trans('seo.comments')">
            <x-card title="Comments" class="flex-1">
                <x-table
                        :headers="[
                    ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
                    ['key' => 'user.name', 'label' => 'User', 'class' => 'w-1'],
                    ['key' => 'comment', 'label' => 'Comments'],
                ]"
                        :rows="$comments"
                        with-pagination
                />
            </x-card>
        </x-tab>
    </x-tabs>

</div>

