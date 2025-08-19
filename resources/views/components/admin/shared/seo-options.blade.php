<div {{$attributes->merge(['class'=>'grid grid-cols-1 gap-4'])}} >
    <x-input :label="trans('validation.attributes.slug')"
             wire:model="slug"
    />
    <x-input :label="trans('validation.attributes.seo_title')"
             wire:model="seo_title"
             class="w-full"
    />
    <x-input :label="trans('validation.attributes.seo_description')"
             wire:model="seo_description"
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
