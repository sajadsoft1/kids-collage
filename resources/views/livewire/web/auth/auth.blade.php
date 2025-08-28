<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}"
      class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Authentication</title>
    <meta name="description" content="Secure authentication for {{ config('app.name') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet"
          type="text/css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="">

<main class="bg-white dark:bg-base-300">

    <div class="relative flex">

        <!-- Content -->
        <div class="w-full md:w-1/2">

            <div class="min-h-screen h-full flex flex-col after:flex-1">

                <!-- Header -->
                <x-nav sticky class="!border-0">
                    <x-slot:brand>
                        <div class="flex items-center gap-3">
                            <x-button icon="o-home" :link="localized_route('home-page')" class="w-8 h-8 text-primary btn-ghost" />
                        </div>
                    </x-slot:brand>
                    <x-slot:actions>
                        <div class="flex items-center gap-2">
                            {{-- Theme Switcher --}}
                            <x-theme-toggle />
                            {{-- Mobile Menu Toggle --}}
                            <label for="main-drawer" class="lg:hidden">
                                <x-icon name="o-bars-3" class="w-6 h-6 cursor-pointer" />
                            </label>
                        </div>
                    </x-slot:actions>
                </x-nav>

                <div class="max-w-sm mx-auto px-4 py-8">

                    {{ $slot }}

                </div>

            </div>

        </div>

        <!-- Image -->
        <div class="hidden md:block absolute top-0 bottom-0 left-0 md:w-1/2" aria-hidden="true">
            <img class="object-cover object-center w-full h-full" src="{{asset('assets/web/img/faeture-bg.jpg')}}" width="760" height="1024" alt="Authentication image"/>
        </div>

    </div>

</main>
<!-- Toast Notifications -->
<x-toast position="toast-top toast-center"/>

@livewireScripts

<!-- Additional Scripts -->
<script>
    // Auto-hide toasts after 5 seconds
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            const toasts = document.querySelectorAll('[data-toast]');
            toasts.forEach(toast => {
                if (toast.style.display !== 'none') {
                    toast.style.display = 'none';
                }
            });
        }, 5000);
    });
</script>
</body>

</html>
