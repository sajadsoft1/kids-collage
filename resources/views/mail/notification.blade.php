<x-mail::message>
    @if ($title)
        # {{ $title }}
    @endif

    @if ($subtitle)
        {{ $subtitle }}
    @endif

    @if ($body)
        {!! nl2br(e($body)) !!}
    @endif

    @if ($cta && isset($cta['url']) && isset($cta['label']))
        <x-mail::button :url="$cta['url']">
            {{ $cta['label'] }}
        </x-mail::button>
    @endif

    <x-mail::subcopy>
        If you're having trouble clicking the button, copy and paste the URL below
        into your web browser: [{{ $cta['url'] ?? '' }}]({{ $cta['url'] ?? '' }})
    </x-mail::subcopy>
</x-mail::message>
