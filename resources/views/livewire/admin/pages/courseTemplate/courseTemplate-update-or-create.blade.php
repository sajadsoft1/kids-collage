@php use App\Enums\BooleanEnum;use App\Enums\CourseLevelEnum;use App\Enums\CourseTypeEnum;use App\Helpers\Constants; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>

    <x-tabs wire:model="selectedTab">


        <x-tab name="informations-tab" label="Informations" icon="o-users">

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="col-span-2 grid grid-cols-1 gap-4">
                    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                        <div class="grid grid-cols-1 gap-4">
                            <x-input :label="trans('validation.attributes.title')"
                                     wire:model="title"
                            />
                            <x-input :label="trans('validation.attributes.description')"
                                     wire:model="description"
                            />
                            <x-admin.shared.tinymce wire:model.blur="body"/>

                        </div>
                    </x-card>
                </div>
                <div class="col-span-1">

                    <x-card shadow separator progress-indicator="submit">
                        <x-input :label="trans('validation.attributes.session_count')"
                                 type="number"
                                 wire:model.live="sessions_count"
                        />
                        <x-select :label="trans('validation.attributes.category')" wire:model="category_id" :options="$categories" required/>
                        <x-tags :label="trans('validation.attributes.tags')" wire:model="tags" icon="o-tag" clearable/>
                    </x-card>

                    <x-card shadow separator progress-indicator="submit" class="mt-5">
                        <x-select :label="trans('validation.attributes.type')"
                                  wire:model="type"
                                  :options="CourseTypeEnum::options()"
                                  option-label="label"
                                  option-value="value" required/>

                        <x-select :label="trans('validation.attributes.level')"
                                  wire:model="level"
                                  :options="CourseLevelEnum::options()"
                                  option-label="label"
                                  option-value="value" required/>
                    </x-card>

                    <x-card shadow separator progress-indicator="submit" class="mt-5">
                        <x-choices label="prerequisites" wire:model="prerequisites" :options="$prerequisitesList" compact />
                    </x-card>


                    <x-card :title="trans('general.page_sections.upload_image')" shadow separator progress-indicator="submit" class="mt-5">
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
            @foreach(range(1,$sessions_count) as $index => $item)


                    <x-collapse name="group_{{$index}}" class="bg-white">
                        <x-slot:heading>
                            <div class="p-5">
                                <div class="flex items-center space-x-3 mb-5">
                                    <div class="flex w-12 h-12 bg-primary text-white rounded-full text-center text-2xl justify-center items-center" >{{$item}}</div>
                                    <div class="grow">
                                        <x-input placeholder="What's happening, Mark?" class="" />
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="grow flex space-x-5">
                                        <button class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-700">
                                            <svg class="w-4 h-4 fill-indigo-400 me-2" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 0h2v16H0V0Zm14 0h2v16h-2V0Zm-3 7H5c-.6 0-1-.4-1-1V1c0-.6.4-1 1-1h6c.6 0 1 .4 1 1v5c0 .6-.4 1-1 1ZM6 5h4V2H6v3Zm5 11H5c-.6 0-1-.4-1-1v-5c0-.6.4-1 1-1h6c.6 0 1 .4 1 1v5c0 .6-.4 1-1 1Zm-5-2h4v-3H6v3Z" />
                                            </svg>
                                            <span>Media</span>
                                        </button>
                                        <button class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-700">
                                            <svg class="w-4 h-4 fill-indigo-400 me-2" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6.974 14c-.3 0-.7-.2-.9-.5l-2.2-3.7-2.1 2.8c-.3.4-1 .5-1.4.2-.4-.3-.5-1-.2-1.4l3-4c.2-.3.5-.4.9-.4.3 0 .6.2.8.5l2 3.3 3.3-8.1c0-.4.4-.7.8-.7s.8.2.9.6l4 8c.2.5 0 1.1-.4 1.3-.5.2-1.1 0-1.3-.4l-3-6-3.2 7.9c-.2.4-.6.6-1 .6Z" />
                                            </svg>
                                            <span>GIF</span>
                                        </button>
                                        <button class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-700">
                                            <svg class="w-4 h-4 fill-indigo-400 me-2" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.793 10.002a.5.5 0 0 1 .353.853l-1.792 1.793a.5.5 0 0 1-.708 0l-1.792-1.793a.5.5 0 0 1 .353-.853h3.586Zm5.014-4.63c1.178 2.497 1.833 5.647.258 7.928-1.238 1.793-3.615 2.702-7.065 2.702S2.173 15.092.935 13.3c-1.575-2.28-.92-5.431.258-7.927A2.962 2.962 0 0 1 0 3.002a3 3 0 0 1 3-3c.787 0 1.496.309 2.029.806a5.866 5.866 0 0 1 5.942 0A2.96 2.96 0 0 1 13 .002a3 3 0 0 1 3 3c0 .974-.472 1.827-1.193 2.37Zm-1.387 6.79c1.05-1.522.417-3.835-.055-5.078C12.915 5.89 11.192 2.002 8 2.002s-4.914 3.89-5.365 5.082c-.472 1.243-1.106 3.556-.055 5.079.843 1.22 2.666 1.839 5.42 1.839s4.577-.62 5.42-1.84ZM6.67 6.62c.113.443.102.68-.433 1.442-.535.761-1.06 1.297-1.658 1.297-.597 0-1.08-.772-1.07-1.483.01-.71.916-2.306 1.997-2.306.784 0 1.05.607 1.163 1.05Zm3.824-1.05c1.08 0 1.987 1.596 1.997 2.306.01.71-.473 1.483-1.07 1.483-.598 0-1.123-.536-1.658-1.297-.535-.762-.546-1-.432-1.442.113-.443.38-1.05 1.163-1.05Z" />
                                            </svg>
                                            <span>Emoji</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </x-slot:heading>
                        <x-slot:content>
                            <x-textarea placeholder="What's happening, Mark?" class="" />
                        </x-slot:content>
                    </x-collapse>


            @endforeach
            </x-accordion>
        </x-tab>


    </x-tabs>


    <x-admin.shared.form-actions/>
</form>
