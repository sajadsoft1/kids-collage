<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    {{-- Vazirmatn font for RTL --}}
    @if (app()->getLocale() === 'fa')
        <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet"
            type="text/css" />
    @endif

    {{-- Only load CSS from Vite, not JS --}}
    @vite(['resources/css/app.css'])

    @livewireStyles
</head>

<body class="flex flex-col min-h-screen bg-base-200">
    {{ $slot }}

    {{-- TOAST area --}}
    <x-toast position="toast-bottom toast-end" />

    {{-- Use traditional Livewire scripts --}}
    @livewireScripts

    {{-- Additional scripts --}}
    @stack('scripts')
</body>

</html>
