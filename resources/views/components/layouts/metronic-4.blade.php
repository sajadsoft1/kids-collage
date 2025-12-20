<!DOCTYPE html>
<html class="h-full" data-theme="true" data-theme-mode="{{ session('theme_mode', 'light') }}"
    dir="{{ session('direction', 'ltr') }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{ $head ?? '' }}
</head>

<body class="antialiased flex h-full text-base text-gray-700 bg-[#F6F6F9] dark:bg-[#1e1e2d] lg:overflow-hidden">

    <!-- Theme Mode Script -->
    <script>
        const defaultThemeMode = 'light';
        let themeMode;

        if (document.documentElement) {
            if (localStorage.getItem('theme')) {
                themeMode = localStorage.getItem('theme');
            } else if (document.documentElement.hasAttribute('data-theme-mode')) {
                themeMode = document.documentElement.getAttribute('data-theme-mode');
            } else {
                themeMode = defaultThemeMode;
            }

            if (themeMode === 'system') {
                themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            document.documentElement.classList.add(themeMode);
        }

        // RTL/LTR Support
        if (localStorage.getItem('direction')) {
            document.documentElement.setAttribute('dir', localStorage.getItem('direction'));
        }
    </script>

    <!-- Page -->
    <div class="flex grow" x-data="{
        themeMode: '{{ session('theme_mode', 'light') }}',
        direction: '{{ session('direction', 'ltr') }}',
        sidebarOpen: false,
        init() {
            const stored = localStorage.getItem('theme');
            if (stored) {
                this.themeMode = stored;
            }
            const dir = localStorage.getItem('direction');
            if (dir) {
                this.direction = dir;
            }
        },
        toggleTheme() {
            this.themeMode = this.themeMode === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.themeMode);
            document.documentElement.setAttribute('data-theme-mode', this.themeMode);
            document.documentElement.classList.remove('light', 'dark');
            document.documentElement.classList.add(this.themeMode);
        },
        toggleDirection() {
            this.direction = this.direction === 'ltr' ? 'rtl' : 'ltr';
            document.documentElement.setAttribute('dir', this.direction);
            localStorage.setItem('direction', this.direction);
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        }
    }">

        <!-- Mobile Header -->
        <x-metronic-4-header />

        <!-- Wrapper -->
        <div class="flex flex-col lg:flex-row grow pt-[60px] lg:pt-0">

            <!-- Sidebar -->
            <x-metronic-4-sidebar />

            <!-- Main Content -->
            <div
                class="flex grow rounded-xl bg-white dark:bg-[#1e1e2d] border border-gray-300 dark:border-gray-200 lg:ms-[290px] mt-0 lg:mt-5 m-5">
                <div class="flex flex-col grow lg:overflow-y-auto lg:[scrollbar-width:auto] pt-5"
                    id="scrollable_content">

                    <main class="grow" role="content">

                        <!-- Toolbar / Breadcrumb -->
                        @if (isset($toolbar) || isset($breadcrumbs) || isset($pageTitle))
                            <div class="pb-5">
                                <div class="container-fluid flex items-center justify-between flex-wrap gap-3">

                                    <!-- Left: Title & Breadcrumbs -->
                                    <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                                        @if (isset($pageTitle))
                                            <h1 class="font-medium text-base text-gray-900 dark:text-gray-100">
                                                {{ $pageTitle }}
                                            </h1>
                                        @endif

                                        @if (isset($breadcrumbs))
                                            <div class="flex items-center flex-wrap gap-1 text-sm">
                                                {{ $breadcrumbs }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Right: Actions -->
                                    @if (isset($toolbar))
                                        <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                                            {{ $toolbar }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Page Content -->
                        <div class="container-fluid">
                            {{ $slot }}
                        </div>

                    </main>

                    <!-- Footer -->
                    <x-metronic-4-footer />

                </div>
            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Wrapper -->

    </div>
    <!-- End of Page -->

    @livewireScripts
    {{ $scripts ?? '' }}

</body>

</html>
