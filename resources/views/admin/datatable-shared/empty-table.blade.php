<div @class([
    'relative block w-full rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 p-12 text-center',
    'hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden',
])>
    @if (isset($icon))
        <x-icon :name="$icon" class="w-12 h-12 mx-auto mb-3 text-base-content/30"/>
    @else
        <svg class="mx-auto text-gray-400 size-12" stroke="currentColor" fill="none" viewBox="0 0 48 48"
             aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m0-4c0 4.418-7.163 8-16 8S8 28.418 8 24m32 10v6m0 0v6m0-6h6m-6 0h-6"/>
        </svg>
    @endif
    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-500">
        {{ $title ?? trans('datatable.empty_table_title') }}
    </h3>
    {{--    <p class="mt-1 text-sm text-gray-500">{{ $description ?? trans('datatable.empty_table_description') }}</p>--}}
    <div class="mt-6">
        @if (isset($link))
            {{--            <x-button :label="trans('datatable.empty_table_btn')" :link="$link ?? null" icon="o-plus" class="btn-primary btn-dash" />--}}
        @endif
    </div>
</div>
