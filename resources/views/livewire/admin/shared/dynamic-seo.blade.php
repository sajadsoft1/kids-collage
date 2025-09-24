@php use App\Models\Comment; @endphp
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
                <div class="my-6 p-4 border rounded-lg bg-base-100 shadow-sm">
                    <p class="text-sm text-gray-500 mb-2">پیش‌نمایش در نتایج گوگل</p>

                    {{-- Title --}}
                    <h2 class="text-[#1a0dab] dark:text-base-content text-xl font-normal truncate">
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
                                 wire:model.live.debounce="slug"
                                 icon="c-link"
                        />
                        <x-input :label="trans('validation.attributes.seo_title')"
                                 wire:model.live.debounce="seo_title"
                                 class="w-full"
                        />
                        <x-textarea :label="trans('validation.attributes.seo_description')"
                                    wire:model.live.debounce="seo_description"
                        />
                        <x-input :label="trans('validation.attributes.canonical')"
                                 wire:model="canonical"
                                 icon-right="o-link"
                                 type="url"
                        />
                        <x-input :label="trans('validation.attributes.old_url')"
                                 wire:model="old_url"
                                 icon-right="o-link"
                                 type="url"
                        />
                        <x-input :label="trans('validation.attributes.redirect_to')"
                                 wire:model="redirect_to"
                                 icon-right="o-link"
                                 type="url"
                        />

                        <x-radio
                                inline
                                :label="trans('validation.attributes.robots_meta')"
                                :options="App\Enums\SeoRobotsMetaEnum::formatedCases()"
                                wire:model="robots_meta"
                                option-value="value"
                                option-label="label"
                                option-hint="hint"
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
                        :value="$viewsCount['all']"
                        icon="lucide.view"/>

                <x-stat
                        :title="trans('seo.stats.comments')"
                        :value="$commentsCount['all']"
                        icon="lucide.message-circle"/>

                <x-stat
                        :title="trans('seo.stats.wishes')"
                        :value="$wishesCount['all']"
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
                        :title="trans('seo.charts.wishes')"
                        :subtitle="trans('seo.from_date_to_date', ['from' => array_first($wishesChart['data']['labels']), 'to' => array_last($wishesChart['data']['labels'])])"
                >
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label" wire:model.live="wishesChartSelectedMonth"/>
                    </x-slot:menu>
                    <x-chart wire:model="wishesChart" id="wishesChartId" wire:key="wishesChartKey"/>
                </x-card>

            </div>

        </x-tab>

        <x-tab name="view-tab" :label="trans('seo.views')">
            <div class="grid md:grid-cols-2 gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <x-admin.shared.stat
                            :title="trans('seo.stats_report.1')"
                            :value="$viewsCount[1]"
                            :icon="$viewsCount[3] > $viewsCount[1] ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                    <x-admin.shared.stat
                            :title="trans('seo.stats_report.3')"
                            :value="$viewsCount[3]"
                            :icon="$viewsCount[6] > $viewsCount[3] ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                    <x-admin.shared.stat
                            :title="trans('seo.stats_report.6')"
                            :value="$viewsCount[6]"
                            :icon="$viewsCount[12] > $viewsCount[6] ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                    <x-admin.shared.stat
                            :title="trans('seo.stats_report.12')"
                            :value="$viewsCount[12]"
                            :icon="$viewsCount[12] > 0 ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                </div>
                <x-card :title="trans('seo.charts.views')"
                        :subtitle="trans('seo.from_date_to_date', ['from' => array_first($viewsChart['data']['labels']), 'to' => array_last($viewsChart['data']['labels'])])">
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label" wire:model.live="viewsChartSelectedMonth"/>
                    </x-slot:menu>
                    <x-chart wire:model="viewsChart" id="viewsChartId" wire:key="viewsChartKey"/>
                </x-card>
            </div>


            <x-card title="Views" class="flex-1 mt-5">
                <x-table
                        :headers="[
                            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
                            ['key' => 'user.full_name', 'label' => trans('datatable.user_name'), ],
                            ['key' => 'created_at', 'label' => trans('datatable.created_at'),'format' => ['date', 'Y/m/d H:i']],
                            ['key' => 'ip', 'label' => trans('validation.attributes.ip_address')],
                        ]"
                        :rows="$views"
                        show-empty-text
                        :empty-text="trans('seo.charts.empty')"
                        with-pagination
                />
            </x-card>
        </x-tab>

        <x-tab name="wish-tab" :label="trans('seo.wished')">
            <div class="grid md:grid-cols-2 gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <x-stat
                            :title="trans('seo.stats_report.1')"
                            :value="$wishesCount[1]"
                            :icon="$wishesCount[3] > $wishesCount[1] ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                    <x-stat
                            :title="trans('seo.stats_report.3')"
                            :value="$wishesCount[3]"
                            :icon="$wishesCount[6] > $wishesCount[3] ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                    <x-stat
                            :title="trans('seo.stats_report.6')"
                            :value="$wishesCount[6]"
                            :icon="$wishesCount[12] > $wishesCount[6] ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                    <x-stat
                            :title="trans('seo.stats_report.12')"
                            :value="$wishesCount[12]"
                            :icon="$wishesCount[12] > 0 ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                </div>
                <x-card :title="trans('seo.charts.wishes')"
                        :subtitle="trans('seo.from_date_to_date', ['from' => array_first($wishesChart['data']['labels']), 'to' => array_last($wishesChart['data']['labels'])])">
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label" wire:model.live="wishesChartSelectedMonth"/>
                    </x-slot:menu>
                    <x-chart wire:model="wishesChart" id="wishesChartId" wire:key="wishesChartKey"/>
                </x-card>
            </div>


            <x-card title="Wished" class="flex-1 mt-5">
                <x-table
                        :headers="[
                            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
                            ['key' => 'user.full_name', 'label' => trans('datatable.user_name') ],
                            ['key' => 'created_at', 'label' => trans('datatable.created_at'),'format' => ['date', 'Y/m/d H:i']],
                        ]"
                        :rows="$wishes"
                        show-empty-text
                        :empty-text="trans('seo.charts.empty')"
                        with-pagination
                />
            </x-card>
        </x-tab>
        <x-tab name="comments-tab" :label="trans('seo.comments')">
            <div class="grid md:grid-cols-2 gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <x-stat
                            :title="trans('seo.stats_report.1')"
                            :description="trans('seo.from_date_to_date', ['from' => $dates[0]['start']->format('Y'), 'to' => $dates[0]['start']->format('Y')])"
                            :value="$commentsCount[1]"
                            :color="$commentsCount[3] > $commentsCount[1] ? 'text-success':'text-secondary'"
                            :icon="$commentsCount[3] > $commentsCount[1] ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                    <x-stat
                            :title="trans('seo.stats_report.3')"
                            :description="trans('seo.from_date_to_date', ['from' => $dates[0]['start']->format('Y'), 'to' => $dates[0]['start']->format('Y')])"
                            :value="$commentsCount[3]"
                            :color="$commentsCount[6] > $commentsCount[3] ? 'text-success':'text-secondary'"
                            :icon="$commentsCount[6] > $commentsCount[3] ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                    <x-stat
                            :title="trans('seo.stats_report.6')"
                            :description="trans('seo.from_date_to_date', ['from' => $dates[0]['start']->format('Y'), 'to' => $dates[0]['start']->format('Y')])"
                            :value="$commentsCount[6]"
                            :color="$commentsCount[12] > $commentsCount[6] ? 'text-success':'text-secondary'"
                            :icon="$commentsCount[12] > $commentsCount[6] ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                    <x-stat
                            :title="trans('seo.stats_report.12')"
                            :description="trans('seo.from_date_to_date', ['from' => $dates[0]['start']->format('Y'), 'to' => $dates[0]['start']->format('Y')])"
                            :value="$commentsCount[12]"
                            :color="$commentsCount[12] > 0 ? 'text-success':'text-secondary'"
                            :icon="$commentsCount[12] > 0 ? 'o-arrow-trending-up':'o-arrow-trending-down'"/>
                    <div class=""></div>
                    <div class=""></div>
                </div>
                <x-card :title="trans('seo.charts.comments')"
                        :subtitle="trans('seo.from_date_to_date', ['from' => array_first($commentsChart['data']['labels']), 'to' => array_last($commentsChart['data']['labels'])])">
                    <x-slot:menu>
                        <x-select :options="$dates" option-value="value" option-label="label" wire:model.live="viewsChartSelectedMonth"/>
                    </x-slot:menu>
                    <x-chart wire:model="commentsChart" id="commentsChartId" wire:key="commentsChartKey"/>
                </x-card>
            </div>


            <x-card title="Comments" class="flex-1 mt-5">
                <x-table
                        :headers="[
                            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
                            ['key' => 'user.full_name', 'label' => trans('datatable.user_name')],
                            ['key' => 'comment', 'label' => trans('validation.attributes.comment')],
                            ['key' => 'admin_note', 'label' => trans('validation.attributes.admin_note')],
                            ['key' => 'published', 'label' => trans('validation.attributes.published'),'format' => fn($row, $field) => $field ? trans('core.published') : trans('core.un_published')],
                            ['key' => 'suggest', 'label' => trans('validation.attributes.suggest')],
                            ['key' => 'rate', 'label' => trans('validation.attributes.rate')],
                        ]"
                        :rows="$comments"
                        :cell-decoration="[
                            'published' => [
                                'bg-warning underline' => fn(Comment $comment) => $comment->published === 0,
                                'bg-success' => fn(Comment $comment) => $comment->published === 1,
                            ],
                        ]"
                        show-empty-text
                        :empty-text="trans('seo.charts.empty')"
                        expandable
                        wire:model="expandedComments"
                        with-pagination
                >
                    @scope('expansion', $row)
                    <div class="bg-base-200 p-8 font-bold">
                        <strong>{{trans('validation.attributes.answer')}}:</strong>
                        <br>
                        {{ $row->admin_note?? trans('comment.no_admin_answer') }}
                    </div>
                    @endscope
                </x-table>
            </x-card>

        </x-tab>
    </x-tabs>

</div>

