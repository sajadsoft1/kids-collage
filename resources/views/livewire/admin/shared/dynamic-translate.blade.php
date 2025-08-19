<div class="">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <x-card class="m-5" label="ترجمه" shadow separator progress-indicator="submit">
        <x-tabs
                active-class="bg-primary rounded !text-white"
                label-class="font-semibold"
                label-div-class="bg-primary/5 rounded w-fit p-2"
                wire:model="translate_modal_tab">

            @foreach(config('app.supported_locales') as $local)
                <x-tab
                        :name="$local"
                        :label="trans('language.'.$local)">
                    @foreach($model->translatable??[] as $field)
                        @if($field ==='title')
                            <x-input
                                    wire:model="form.{{$local}}.{{$field}}"
                                    :label="trans('validation.attributes.'.$field)"
                            />
                        @elseif($field ==='description')
                            <x-input
                                    wire:model="form.{{$local}}.{{$field}}"
                                    :label="trans('validation.attributes.'.$field)"
                            />
                        @elseif($field ==='body')
                            <x-admin.shared.tinymce wire:model="form.{{$local}}.{{$field}}" :label="trans('validation.attributes.'.$field)"/>
                        @endif
                    @endforeach
                </x-tab>
            @endforeach
        </x-tabs>

        <div class="">
            <x-button
                    :label="trans('general.submit')"
                    type="submit"
                    spinner="submit"
                    wire:click="submit"/>
            <x-button
                    :label="trans('general.back')"
                    :link="route($this->back_route)"
                    wire:loading.attr="disabled"
                    wire:target="submit"/>
        </div>
    </x-card>
</div>