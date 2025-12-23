<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

@include('components.layouts.shared.head')

<body class="overflow-hidden h-screen font-sans antialiased bg-base-200">

    <div x-data="mainLayoutState()" x-init="init()" class="flex overflow-hidden flex-col h-full md:flex-row">

        <!-- Sidebar -->
        @livewire('admin.shared.sidebar')

        <!-- Main Content -->
        <main class="flex overflow-hidden relative z-10 flex-col flex-1 min-w-0 h-full" x-data="{
            get sidebarOpen() { return $store.sidebar?.sidebarOpen ?? false; },
            get isPinned() { return $store.sidebar?.isPinned ?? true; },
            get isTablet() { return $store.sidebar?.isTablet ?? false; }
        }"
            :class="{
                // فقط transition برای margin-right در حالت pinned (نه برای محتوا)
                'transition-[margin-right] duration-300 ease-out': isPinned && !isTablet,
                'lg:mr-[332px]': sidebarOpen && isPinned && !isTablet,
                'md:mr-[72px]': !sidebarOpen || !isPinned || isTablet
            }"
            :style="isPinned && !isTablet ? 'will-change: margin-right;' : ''"
            @click="$store.sidebar?.closeMenuIfOverlay()">

            <!-- Header - Glass Effect -->
            <header
                class="flex fixed top-0 right-0 left-0 z-40 justify-between items-center px-4 w-full h-14 border-b glass-header md:px-6 md:h-14 shrink-0 md:relative border-base-300 dark:border-base-content/10">
                <!-- Right Side: Menu Toggle & Breadcrumb -->
                <div class="flex gap-4 items-center">
                    <!-- Mobile Menu Toggle -->
                    <button @click="$store.sidebar?.toggleSidebar()"
                        class="btn btn-ghost btn-square md:hidden text-base-content/60" aria-label="باز/بستن منو">
                        <i class="text-lg fas fa-bars"></i>
                    </button>

                    <!-- Breadcrumb - Hidden on Mobile -->
                    <nav class="hidden gap-2 items-center text-sm sm:flex" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}" wire:navigate
                            class="font-medium transition-colors text-base-content/50 hover:text-primary">
                            پیشخوان
                        </a>
                        <i class="text-xs fas text-base-content/30"
                            :class="document.documentElement.dir === 'rtl' ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
                        <span class="font-bold text-primary"
                            x-text="$store.sidebar?.getBreadcrumb() || 'داشبورد'"></span>
                        <template x-if="$store.sidebar?.currentPageTitle">
                            <span class="flex gap-2 items-center">
                                <i class="text-xs fas text-base-content/30"
                                    :class="document.documentElement.dir === 'rtl' ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
                                <span class="text-base-content/60" x-text="$store.sidebar?.currentPageTitle"></span>
                            </span>
                        </template>
                    </nav>
                </div>

                <!-- Left Side: Actions -->
                <div class="flex gap-3 items-center">
                    <!-- Search Button - Desktop Only -->
                    <button
                        class="hidden rounded-xl md:flex btn btn-ghost btn-sm btn-square text-base-content/50 hover:bg-base-200">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Notifications -->
                    <button
                        class="relative rounded-xl btn btn-ghost btn-sm btn-square text-base-content/50 hover:bg-base-200">
                        <i class="fas fa-bell"></i>
                        <span
                            class="absolute top-1 right-1 w-2 h-2 rounded-full border-2 bg-error border-base-100"></span>
                    </button>

                    <!-- Profile Button -->
                    <button
                        @click="$store.sidebar?.openMenu('profile'); $store.sidebar.menuVisible = true; $store.sidebar.sidebarOpen = true"
                        class="overflow-hidden w-10 h-10 rounded-xl border-2 border-transparent transition-all hover:border-primary"
                        aria-label="پروفایل کاربر">
                        <div
                            class="flex justify-center items-center w-full h-full text-sm font-bold bg-primary text-primary-content">
                            JD
                        </div>
                    </button>
                </div>
            </header>

            <!-- Scrollable Content Container -->
            <div class="overflow-y-auto flex-1 mt-14 transition-all duration-300 bg-base-200 md:mt-0">
                <div class="container p-4 mx-auto space-y-6 md:p-6 lg:p-10">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <footer class="px-6 py-4 border-t bg-base-100 sidebar-border">
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
            class="fixed inset-0 z-40 backdrop-blur-sm bg-black/50"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>
    </div>

    @include('components.layouts.shared.shared')
</body>

</html>
