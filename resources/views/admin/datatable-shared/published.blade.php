<div @class([
        'flex items-center justify-start',
        ['text-danger','text-success'][$row->published->value],
    ])>
    <x-icon
            name="fas.database"
            class="h-3.5 w-3.5 stroke-[1.7]"
    />
    <div class="ms-1.5 whitespace-nowrap">
        {{ $row->published->title() }}
    </div>
</div>