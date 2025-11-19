@props([
    'config' => [],
    'label' => trans('validation.attributes.body'),
])

@php
    $defaultConfig = [
        'plugins' => 'autoresize',
        'height' => 1500,
        'min_height' => 1000,
        'max_height' => 1500,
        'statusbar' => true,
        'branding' => false,
        'menubar' => 'file edit view insert format tools table help',
        'menu' => [],
        'plugins' =>
            'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount autoresize',
        'toolbar' =>
            'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code fullscreen',
        'quickbars_selection_toolbar' => 'bold italic | quicklink h2 h3 blockquote',
        'contextmenu' => 'link image imagetools table',
    ];

    $mergedConfig = array_replace_recursive($defaultConfig, $config);
@endphp

<div class="{{ $attributes->get('class:div') }}">
    <x-editor :label="$label" {{ $attributes }} disk="tinymce"
              :config="$mergedConfig"/>
</div>
