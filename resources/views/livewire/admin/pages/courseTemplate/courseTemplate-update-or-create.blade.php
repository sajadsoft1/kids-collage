@php use App\Enums\BooleanEnum;use App\Enums\CourseLevelEnum;use App\Enums\CourseTypeEnum;use App\Helpers\Constants; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>

    <x-tabs wire:model="selectedTab">


        <x-tab name="informations-tab" label="Informations" icon="o-users">

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="col-span-2 grid grid-cols-1 gap-4">
                    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                        <div class="grid grid-cols-1 gap-4">
                            <x-input :label="trans('validation.attributes.title')" wire:model="title" required/>
                            <x-input :label="trans('validation.attributes.description')" wire:model="description"
                                     required/>
                            <x-admin.shared.tinymce wire:model.blur="body"/>
                        </div>
                    </x-card>
                </div>
                <div class="col-span-1">

                    <x-card shadow separator progress-indicator="submit">
                        <x-input :label="trans('validation.attributes.session_count')"
                                 type="number"
                                 min="1"
                                 :readonly="$model->id"
                                 wire:model.blur="sessions_count"
                                 required
                        />
                        <x-select :label="trans('validation.attributes.category')" wire:model="category_id"
                                  :options="$categories" required
                                  :placeholder="trans('general.please_select_an_option')" placeholder-value="0"
                        />
                        <x-tags :label="trans('validation.attributes.tags')" wire:model="tags" icon="o-tag" clearable/>
                    </x-card>

                    <x-card shadow separator progress-indicator="submit" class="mt-5">
                        <x-group :label="trans('validation.attributes.type')"
                                 wire:model="type"
                                 :options="CourseTypeEnum::options()"
                                 option-label="label"
                                 option-value="value"
                                 class="[&:checked]:!btn-primary btn-sm"
                                 required/>

                        <x-group :label="trans('validation.attributes.level')"
                                 wire:model="level"
                                 :options="CourseLevelEnum::options()"
                                 option-label="label"
                                 option-value="value"
                                 class="[&:checked]:!btn-primary btn-sm"
                                 required/>
                    </x-card>

                    <x-card shadow separator progress-indicator="submit" class="mt-5">
                        <x-choices label="prerequisites" wire:model="prerequisites" :options="$prerequisitesList"
                                   compact/>
                    </x-card>


                    <x-card :title="trans('general.page_sections.upload_image')" shadow separator
                            progress-indicator="submit" class="mt-5">
                        <x-admin.shared.single-file-upload
                            :ratio="1280/720"
                            :hint="croperHint(Constants::RESOLUTION_1280_720)"
                            :default_image="$model->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720)"/>
                    </x-card>


                </div>

            </div>

        </x-tab>


        <x-tab name="tricks-tab" label="Sessions" icon="o-sparkles">

            <x-accordion wire:model="group">
                @foreach($sessions as $index => $session)

                    <x-collapse name="group_{{$index}}" class="bg-white">
                        <x-slot:heading>
                            <div class="p-5">
                                <div class="flex items-center space-x-3 mb-5">
                                    <div
                                        class="flex w-12 h-12 bg-primary text-white rounded-full text-center text-2xl justify-center items-center">{{$session['order']}}</div>
                                    <div class="grow">
                                        <x-input wire:model="sessions.{{$index}}.title" class="" required/>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="grow flex space-x-5">
                                        <div
                                            class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-700">
                                            <span>
                                                <x-input
                                                    icon="lucide.timer"
                                                    wire:model="sessions.{{$index}}.duration_minutes"
                                                    suffix="دقیقه"
                                                    type="number"
                                                    class="max-w-60"/>
                                            </span>
                                        </div>
                                        <div
                                            class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-700">
                                            <span>
                                                <x-select
                                                    wire:model="sessions.{{$index}}.type"
                                                    :options="CourseTypeEnum::options()"
                                                    icon="lucide.building-2"
                                                    option-label="label"
                                                    option-value="value"
                                                    class="max-w-60 min-w-40 fill-primary"/>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </x-slot:heading>
                        <x-slot:content>
                            <x-textarea
                                wire:model="sessions.{{$index}}.description"
                                :label="trans('validation.attributes.description')"
                                :placeholder="trans('coursetemplate.page.description_of_session')"
                                rows="5"
                                inline/>
                        </x-slot:content>
                    </x-collapse>

                @endforeach
            </x-accordion>
        </x-tab>


    </x-tabs>


    <x-admin.shared.form-actions/>
</form>
