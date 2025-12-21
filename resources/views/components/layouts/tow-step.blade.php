<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

@include('components.layouts.shared.head')

<body class="antialiased bg-base-200 font-sans h-screen overflow-hidden">

    <div x-data="mainLayoutState()" x-init="init()" class="flex flex-col h-full md:flex-row overflow-hidden">

        <!-- Sidebar -->
        @livewire('admin.shared.sidebar')

        <!-- Main Content -->
        <main
            class="flex flex-col flex-1 transition-all duration-300 ease-out min-w-0 z-10 relative h-full overflow-hidden"
            x-data="{
                get sidebarOpen() { return $store.sidebar?.sidebarOpen ?? false; },
                get isPinned() { return $store.sidebar?.isPinned ?? true; },
                get isTablet() { return $store.sidebar?.isTablet ?? false; }
            }"
            :class="{
                'lg:mr-[332px]': sidebarOpen && isPinned && !isTablet,
                'md:mr-[72px]': !sidebarOpen || !isPinned || isTablet
            }"
            @click="$store.sidebar?.closeMenuIfOverlay()">

            <!-- Header - Glass Effect -->
            <header
                class="glass-header px-4 md:px-8 h-16 md:h-20 flex items-center justify-between shrink-0 w-full fixed top-0 left-0 right-0 md:relative z-40">
                <!-- Right Side: Menu Toggle & Breadcrumb -->
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Toggle -->
                    <button @click="$store.sidebar?.toggleSidebar()"
                        class="btn btn-ghost btn-square md:hidden text-base-content/60" aria-label="باز/بستن منو">
                        <i class="text-lg fas fa-bars"></i>
                    </button>

                    <!-- Breadcrumb - Hidden on Mobile -->
                    <nav class="hidden sm:flex items-center gap-2 text-sm" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}" wire:navigate
                            class="text-base-content/50 hover:text-primary transition-colors font-medium">
                            پیشخوان
                        </a>
                        <i class="fas text-xs text-base-content/30"
                            :class="document.documentElement.dir === 'rtl' ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
                        <span class="font-bold text-primary"
                            x-text="$store.sidebar?.getBreadcrumb() || 'داشبورد'"></span>
                        <template x-if="$store.sidebar?.currentPageTitle">
                            <span class="flex items-center gap-2">
                                <i class="fas text-xs text-base-content/30"
                                    :class="document.documentElement.dir === 'rtl' ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
                                <span class="text-base-content/60" x-text="$store.sidebar?.currentPageTitle"></span>
                            </span>
                        </template>
                    </nav>
                </div>

                <!-- Left Side: Actions -->
                <div class="flex items-center gap-3">
                    <!-- Search Button - Desktop Only -->
                    <button
                        class="hidden md:flex btn btn-ghost btn-sm btn-square rounded-xl text-base-content/50 hover:bg-base-200">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Notifications -->
                    <button
                        class="btn btn-ghost btn-sm btn-square rounded-xl text-base-content/50 hover:bg-base-200 relative">
                        <i class="fas fa-bell"></i>
                        <span
                            class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full border-2 border-base-100"></span>
                    </button>

                    <!-- Profile Button -->
                    <button
                        @click="$store.sidebar?.openMenu('profile'); $store.sidebar.menuVisible = true; $store.sidebar.sidebarOpen = true"
                        class="w-10 h-10 rounded-xl overflow-hidden border-2 border-transparent hover:border-primary transition-all"
                        aria-label="پروفایل کاربر">
                        <div
                            class="w-full h-full bg-primary text-primary-content flex items-center justify-center text-sm font-bold">
                            JD
                        </div>
                    </button>
                </div>
            </header>

            <!-- Scrollable Content Container -->
            <div class="flex-1 overflow-y-auto bg-base-200 transition-all duration-300 mt-16 md:mt-0">
                <div class="p-4 md:p-6 lg:p-10 space-y-6">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <footer class="px-6 py-4 bg-base-100 sidebar-border border-t">
                <div class="flex flex-col gap-2 justify-between items-center md:flex-row">
                    <p class="text-xs text-base-content/40">© ۱۴۰۳ پنل مدیریت</p>
                    <p class="text-xs text-base-content/40">نسخه ۱.۰.۰</p>
                </div>
            </footer>
        </main>

        <!-- Mobile/Tablet Overlay - Shows on mobile fullscreen and tablet when level 2 is floating -->
        <div x-data="{
            get menuVisible() { return $store.sidebar?.menuVisible ?? false; },
            get isMobile() { return $store.sidebar?.isMobile ?? false; },
            get isTablet() { return $store.sidebar?.isTablet ?? false; },
            get shouldShowOverlay() { return this.menuVisible && (this.isMobile || this.isTablet); }
        }" x-show="shouldShowOverlay" @click="$store.sidebar?.closeMobile()"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>
    </div>

    @include('components.layouts.shared.shared')
</body>

</html>
