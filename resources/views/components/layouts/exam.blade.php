<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">

@include('components.layouts.shared.head')

<body class="flex flex-col min-h-screen bg-base-200">
    {{ $slot }}

    @include('components.layouts.shared.shared')
</body>

</html>
