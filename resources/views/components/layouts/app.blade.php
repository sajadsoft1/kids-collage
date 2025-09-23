<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="/assets/css/cropper.min.css" />
    <script src="/assets/js/cropper.min.js"></script>
    <script src="/assets/js/tinymce/tinymce.min.js"></script>
    <script src="/assets/js/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<div x-data="{
    open: localStorage.getItem('sidebar_open') === 'true' ? true : false,
    isDesktop: window.innerWidth >= 1024,

    init() {
        // Set initial state based on screen size
        this.isDesktop = window.innerWidth >= 1024;

        // Watch for screen size changes
        window.addEventListener('resize', () => {
            const wasDesktop = this.isDesktop;
            this.isDesktop = window.innerWidth >= 1024;

            // If switching to desktop, open sidebar
            if (this.isDesktop && !wasDesktop) {
                this.open = true;
                localStorage.setItem('sidebar_open', 'true');
            }
            // If switching to mobile, close sidebar
            else if (!this.isDesktop && wasDesktop) {
                this.open = false;
                localStorage.setItem('sidebar_open', 'false');
            }
        });

        // Watch for open state changes and save to localStorage
        this.$watch('open', (value) => {
            localStorage.setItem('sidebar_open', value.toString());
        });
    }
}" @keydown.window.escape="open = false" class="flex flex-col min-h-screen bg-base-300">

    @include('admin.layouts.navbar-mobile')


    <!-- Static sidebar for desktop -->
    @include('admin.layouts.navbar')



    <div class="flex flex-col flex-1" x-bind:class="open ? 'lg:ps-72' : 'lg:ps-0'">
        @include('admin.layouts.header')
        <main class="h-full flex-1 container mx-auto">
            <div class="px-4 sm:px-6 lg:px-8 h-full">
                <div class="container">
                    {{ $slot }}
                </div>
            </div>
        </main>
        @include('admin.layouts.footer')
    </div>




</div>

{{--  TOAST area --}}
<x-toast position="toast-button toast-end" />

@livewireScripts
@livewireCalendarScripts
</body>

</html>
