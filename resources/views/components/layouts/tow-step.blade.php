<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

@include('components.layouts.shared.head')

<body class="antialiased bg-base-200">

    <div x-data="mainLayoutState()" x-init="init()" class="flex flex-col min-h-screen md:flex-row">

        <!-- Mobile/Tablet Header -->
        <header
            class="flex fixed top-0 right-0 left-0 z-40 justify-between items-center px-4 h-14 md:hidden bg-slate-900">
            <button @click="$store.sidebar?.toggleSidebar()"
                class="flex justify-center items-center w-10 h-10 rounded-lg transition-all text-slate-400 hover:bg-slate-800 hover:text-white">
                <i class="text-lg fas fa-bars"></i>
            </button>
            <h1 class="text-lg font-bold text-white">پنل مدیریت</h1>
            <div
                class="flex justify-center items-center w-10 h-10 text-sm font-medium text-white bg-blue-600 rounded-lg">
                JD
            </div>
        </header>

        <!-- Sidebar -->
        @livewire('admin.shared.sidebar')

        <!-- Main Content -->
        <main class="flex flex-col flex-1 mt-14 transition-all duration-300 md:mt-0" x-data="{
            get sidebarOpen() { return $store.sidebar?.sidebarOpen ?? false; },
            get menuVisible() { return $store.sidebar?.menuVisible ?? false; }
        }"
            :class="menuVisible ? 'md:mr-[310px]' : (sidebarOpen ? 'md:mr-[310px]' : 'md:mr-[70px]')"
            @click="$store.sidebar?.closeMenuIfOverlay()">

            <div class="container flex-1 p-4 mx-auto md:p-6">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <footer class="px-6 py-4 bg-white border-t border-slate-200">
                <div class="flex flex-col gap-2 justify-between items-center md:flex-row">
                    <p class="text-xs text-slate-400">© ۱۴۰۳ پنل مدیریت</p>
                    <p class="text-xs text-slate-400">نسخه ۱.۰.۰</p>
                </div>
            </footer>
        </main>

        <!-- Mobile Overlay -->
        <div x-data="{ get menuVisible() { return $store.sidebar?.menuVisible ?? false; } }" x-show="menuVisible" @click="$store.sidebar?.closeMenu()"
            class="fixed inset-0 z-40 bg-black/50 md:hidden" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
        </div>
    </div>

    @include('components.layouts.shared.shared')
</body>

</html>
