<x-badge :value="$row->status->title()"
        @class([
                'badge-dash',
                'badge-soft',
        'badge-primary'=> $row->status->value,
        'badge-error'=> !$row->status->value
        ])/>