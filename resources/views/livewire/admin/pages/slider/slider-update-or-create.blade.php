@php
    use App\Enums\BooleanEnum;
    use App\Helpers\Constants;
@endphp
<form wire:submit.prevent="submit" enctype="multipart/form-data">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

        <div class="grid grid-cols-1 col-span-2 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <x-input :label="trans('validation.attributes.title')" wire:model="title" required />
                    <x-textarea :label="trans('validation.attributes.description')" wire:model="description" required />

                    <x-input :label="trans('validation.attributes.link')" wire:model="link" type="url" />

                    <x-admin.shared.smart-datetime :label="trans('validation.attributes.expired_at')" wire:model="expired_at" />


                </div>
            </x-card>

            <x-card :title="trans('general.roles')" shadow separator progress-indicator="submit" class="mt-5 relative min-h-[400px]">
                <x-slot:menu>
                    <x-button icon="o-plus" class="btn-circle btn-sm" wire:click="addRole" />
                </x-slot:menu>
                @if (count($roles) > 0)
                    <table class="table">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>نوع</th>
                                <th>مقدار</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- row 1 -->
                            @foreach ($roles as $index => $role)
                                <tr wire:key="role-{{ $index }}">
                                    <td class="p-1 w-1/2 sm:p-2">
                                        <x-select :options="$reference_type" wire:model.live="roles.{{ $index }}.type" />
                                    </td>
                                    <td class="p-1 w-1/2 sm:p-2">
                                        @switch($role['type'])
                                            @case(\App\Models\Category::class)
                                                <x-choices :options="$categories" wire:key="role-{{ $index }}-value"
                                                    wire:model.live="roles.{{ $index }}.value" compact />
                                            @break

                                            @case(\App\Models\Tag::class)
                                                <x-choices :options="$tags" wire:key="role-{{ $index }}-value"
                                                    wire:model.live="roles.{{ $index }}.value" compact />
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="p-1 w-0 sm:p-2">
                                        <x-button type="button" variant="danger" size="sm" icon="s-trash"
                                            wire:click="deleteRole({{ $index }})" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                @if (count($roles) === 0)
                    @include('admin.datatable-shared.empty-table', [
                        'title' => trans('datatable.empty_table_title'),
                        'description' => trans('datatable.empty_table_description'),
                        'btn' => trans('datatable.empty_table_btn'),
                    ])
                @endif

            </x-card>

        </div>
        <div class="col-span-1">
            <div class="sticky top-20">
                <x-card :title="trans('general.page_sections.upload_image')" shadow separator progress-indicator="submit" class="">
                    <x-admin.shared.single-file-upload wire_model="image" :default_image="$model->getFirstMediaUrl('image', Constants::RESOLUTION_1280_400)" :crop_after_change="true"
                        :ratio="16 / 9" required />
                </x-card>

                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit" class="mt-5">
                    <x-admin.shared.published-config :has-published-at="true" :default-date="$published_at" />
                </x-card>
                <x-card :title="trans('setting.model')" shadow separator progress-indicator="submit" class="mt-5">
                    <div class="grid gap-4 grid-col-1">
                        <x-toggle :label="trans('validation.attributes.has_timer')" wire:model.live="has_timer" right value="1" />
                        <div wire:show="has_timer">
                            <x-admin.shared.smart-datetime :label="trans('validation.attributes.timer_start')" wire-property-name="timer_start" />
                        </div>
                        <x-input :label="trans('validation.attributes.ordering')" wire:model="ordering" type="number" required />
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions />
</form>
