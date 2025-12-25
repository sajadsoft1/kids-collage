@php
    // Sidebar configuration - using standard 72 (18rem = 288px) for consistency
    $sidebarWidth = 72; // rem units
    $breakpoint = 1024; // lg breakpoint in pixels
@endphp
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

<body x-data="{
    // Sidebar state management
    open: (() => {
        const saved = localStorage.getItem('sidebar_open');
        if (saved !== null) return saved === 'true';
        return window.innerWidth >= {{ $breakpoint }};
    })(),
    isDesktop: window.innerWidth >= {{ $breakpoint }},
    sidebarWidth: {{ $sidebarWidth }},

    init() {
        // Set initial state based on screen size
        this.updateDesktopState();

        // Handle resize with debounce for better performance
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.handleResize();
            }, 150);
        });

        // Watch for open state changes and save to localStorage
        this.$watch('open', (value) => {
            localStorage.setItem('sidebar_open', value.toString());
            // Prevent body scroll when mobile sidebar is open
            if (!this.isDesktop) {
                document.body.style.overflow = value ? 'hidden' : '';
            }
        });

        // Watch for desktop state changes
        this.$watch('isDesktop', (value) => {
            if (value) {
                // On desktop, restore body scroll
                document.body.style.overflow = '';
            } else if (this.open) {
                // On mobile with sidebar open, prevent scroll
                document.body.style.overflow = 'hidden';
            }
        });

        // Sync route active states on navigation (for SPA)
        this.setupRouteWatcher();
    },

    setupRouteWatcher() {
        // Force re-evaluation of active menu items after navigation
        const updateActiveStates = () => {
            // Trigger Alpine reactivity for menu items
            this.$nextTick(() => {
                // Dispatch custom event for menu components to update
                window.dispatchEvent(new CustomEvent('route-changed', {
                    detail: { path: window.location.pathname }
                }));
            });
        };

        // Listen for Livewire navigation
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', () => {
                setTimeout(updateActiveStates, 100);
            });
        }

        // Listen for Alpine navigation
        document.addEventListener('alpine:navigated', () => {
            setTimeout(updateActiveStates, 100);
        });

        // Listen for popstate (browser back/forward)
        window.addEventListener('popstate', () => {
            setTimeout(updateActiveStates, 100);
        });
    },

    updateDesktopState() {
        this.isDesktop = window.innerWidth >= {{ $breakpoint }};
    },

    handleResize() {
        const wasDesktop = this.isDesktop;
        this.updateDesktopState();

        // If switching to desktop, open sidebar by default
        if (this.isDesktop && !wasDesktop) {
            this.open = true;
            localStorage.setItem('sidebar_open', 'true');
        }
        // If switching to mobile, close sidebar and restore scroll
        else if (!this.isDesktop && wasDesktop) {
            this.open = false;
            localStorage.setItem('sidebar_open', 'false');
            document.body.style.overflow = '';
        }
    },

    toggleSidebar() {
        this.open = !this.open;
    },

    closeSidebar() {
        this.open = false;
    }
}" @keydown.window.escape="closeSidebar()"
    class="flex flex-col min-h-screen bg-base-200 dark:bg-base-300">

    {{-- Mobile Sidebar --}}
    @include('admin.layouts.navbar-mobile')

    {{-- Desktop Sidebar --}}
    @include('admin.layouts.navbar')

    {{-- Main Content Area --}}
    <div class="flex flex-col flex-1 min-h-0 w-full transition-all duration-300"
        :class="open && isDesktop ? (document.documentElement.dir === 'rtl' ? 'lg:pe-[{{ $sidebarWidth }}rem]' :
            'lg:ps-[{{ $sidebarWidth }}rem]') : ''"
        x-data="{
            toggleSidebar() {
                    const bodyData = Alpine.$data(document.body);
                    if (bodyData) {
                        bodyData.open = !bodyData.open;
                    }
                },
                closeSidebar() {
                    const bodyData = Alpine.$data(document.body);
                    if (bodyData) {
                        bodyData.open = false;
                    }
                }
        }" @toggle-sidebar.window="toggleSidebar()">
        {{-- Header --}}
        @include('admin.layouts.header', [
            'nav_class' => 'bg-base-100 dark:bg-base-200 border-b border-base-300 dark:border-base-content/10',
        ])

        {{-- Main Content --}}
        <main class="flex flex-col flex-1 bg-base-200 dark:bg-base-300">
            <div @class([
                'flex flex-col flex-1',
                'px-4 sm:px-6 lg:px-8 py-6' => !isset($fullWidth) || !$fullWidth,
                'px-0' => isset($fullWidth) && $fullWidth,
            ])>
                <div @class([
                    'flex flex-col flex-1',
                    'container mx-auto' => !isset($fullWidth) || !$fullWidth,
                    'w-full' => isset($fullWidth) && $fullWidth,
                ])>
                    {{ $slot }}
                </div>
            </div>
        </main>

        {{-- Footer --}}
        @include('admin.layouts.footer')
    </div>

    {{-- Toast Notifications --}}
    <x-toast position="toast-top toast-center" />

    @livewireScripts
    @livewireCalendarScripts
</body>

</html>
