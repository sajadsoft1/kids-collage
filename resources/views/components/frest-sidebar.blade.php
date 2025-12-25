@props(['navbarMenu' => []])

@php
    use Illuminate\Support\Arr;
    $navbarMenu = $navbarMenu ?? [];
    $sidebarWidthCollapsed = config('frest.sidebar.width.collapsed', 20);
    $sidebarWidthExpanded = config('frest.sidebar.width.expanded', 72);
@endphp

{{-- Fixed Sidebar - Semi-dark theme with collapsible (always dark mode) --}}
<aside x-data="createFrestSidebar()"
    class="hidden fixed top-0 z-40 flex-col h-screen transition-all duration-300 frest-sidebar-dark bg-base-300 dark:bg-base-100 border-base-content/10 lg:flex rtl:right-0 ltr:left-0 rtl:border-l ltr:border-r"
    :class="collapsed ? 'w-{{ $sidebarWidthCollapsed }}' : 'w-{{ $sidebarWidthExpanded }}'" dir="auto"
    style="background-color: var(--b3) !important; color-scheme: dark !important;">
    <style>
        /* Force dark mode for sidebar and all children */
        .frest-sidebar-dark {
            background-color: var(--b3) !important;
            color-scheme: dark !important;
        }

        .frest-sidebar-dark * {
            color-scheme: dark !important;
        }

        /* Navigation menu styling */
        .frest-sidebar-dark nav ul li a,
        .frest-sidebar-dark nav ul li button {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .frest-sidebar-dark nav ul li a:hover,
        .frest-sidebar-dark nav ul li button:hover {
            color: white !important;
        }

        /* Ensure icons are white */
        .frest-sidebar-dark svg,
        .frest-sidebar-dark svg *,
        .frest-sidebar-dark [class*='icon'],
        .frest-sidebar-dark i {
            color: white !important;
            fill: white !important;
            stroke: white !important;
        }

        /* Text elements in sidebar */
        .frest-sidebar-dark h1,
        .frest-sidebar-dark h2,
        .frest-sidebar-dark h3,
        .frest-sidebar-dark h4 {
            color: white !important;
        }

        /* Submenu items */
        .frest-sidebar-dark .menu li li>*,
        .frest-sidebar-dark .menu li li>a {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .frest-sidebar-dark .menu li li:hover>*,
        .frest-sidebar-dark .menu li li:hover>a {
            color: white !important;
        }

        /* Override any theme-based text colors */
        .frest-sidebar-dark .text-base-content,
        .frest-sidebar-dark [class*='text-base-content'] {
            color: white !important;
        }

        /* Collapsed sidebar popup styling */
        .frest-sidebar-dark .frest-sidebar-popup {
            background-color: var(--b3) !important;
            color-scheme: dark !important;
        }

        .frest-sidebar-dark .frest-sidebar-popup * {
            color-scheme: dark !important;
        }

        /* Popup submenu item hover states */
        .frest-sidebar-dark .frest-sidebar-popup a {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .frest-sidebar-dark .frest-sidebar-popup a:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        /* Active state for popup items */
        .frest-sidebar-dark .frest-sidebar-popup a.bg-primary {
            background-color: var(--color-primary) !important;
            color: white !important;
        }

        /* Ensure icons in popup are white */
        .frest-sidebar-dark .frest-sidebar-popup svg,
        .frest-sidebar-dark .frest-sidebar-popup svg * {
            color: white !important;
            fill: white !important;
            stroke: white !important;
        }

        /* Focus states for accessibility */
        .frest-sidebar-dark nav ul li a:focus-visible,
        .frest-sidebar-dark nav ul li button:focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
            border-radius: 0.5rem;
        }

        .frest-sidebar-dark nav ul li a:focus-visible:not(:hover),
        .frest-sidebar-dark nav ul li button:focus-visible:not(:hover) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Collapse toggle button focus */
        .frest-sidebar-dark button:focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }

        /* Mobile sidebar slide direction (doesn't rely on Tailwind rtl:/ltr: variants) */
        :dir(rtl) .frest-mobile-sidebar-offscreen {
            transform: translateX(100%);
        }

        :dir(ltr) .frest-mobile-sidebar-offscreen {
            transform: translateX(-100%);
        }

        /* Ensure mobile sidebar is properly positioned */
        [data-frest-sidebar="mobile"] {
            will-change: transform;
        }

        /* Prevent body scroll when mobile sidebar is open */
        body:has([data-frest-sidebar="mobile"][x-show="true"]) {
            overflow: hidden;
        }
    </style>
    {{-- Brand Header with Toggle --}}
    <div class="flex relative justify-between items-center px-4 h-16 border-b border-base-content/10">
        <div class="flex gap-3 items-center w-full" :class="collapsed ? 'justify-center' : ''">
            <div class="flex justify-center items-center w-10 h-10 rounded-full bg-primary/20 shrink-0">
                <x-icon name="o-cube" class="w-6 h-6 text-white" />
            </div>
            <div x-show="!collapsed" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-2"
                class="overflow-hidden">
                <h1 class="text-xl font-bold text-white whitespace-nowrap">فرست</h1>
            </div>
        </div>
        {{-- Collapse Toggle Button --}}
        <button @click="toggle()" aria-label="جمع کردن/باز کردن منو"
            class="flex justify-center items-center p-1.5 w-8 h-8 text-white rounded-lg transition-colors bg-base-300/50 hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2"
            :class="collapsed ? '' : 'rotate-180'" type="button">
            <x-icon name="o-chevron-left" class="w-5 h-5 transition-transform duration-300" />
        </button>
    </div>

    {{-- Navigation Menu - Using pre-computed menu data --}}
    <nav class="overflow-y-auto flex-1 px-4 py-4 no-scrollbar" aria-label="منوی اصلی">
        <ul class="flex flex-col gap-1" x-data="{ isRouteActive: window.isRouteActive }">
            @foreach ($navbarMenu as $menu)
                <x-frest-sidebar-menu-item :menu="$menu" />
            @endforeach
        </ul>
    </nav>
</aside>

{{-- Mobile Sidebar Overlay --}}
<div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-30 backdrop-blur-sm bg-black/50 lg:hidden" @click="$dispatch('toggle-sidebar')" x-cloak
    aria-hidden="true">
</div>

{{-- Mobile Sidebar --}}
<aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="frest-mobile-sidebar-offscreen" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="frest-mobile-sidebar-offscreen" x-data="createFrestSidebar({ collapsed: false })"
    class="flex fixed top-0 z-40 flex-col w-72 max-w-[85vw] h-screen frest-sidebar-dark bg-base-300 dark:bg-base-300 lg:hidden border-base-content/10"
    :class="document.documentElement.dir === 'rtl' ? 'right-0 border-l' : 'left-0 border-r'" dir="auto" x-cloak
    data-frest-sidebar="mobile" @keydown.escape.window="$dispatch('toggle-sidebar')"
    style="background-color: var(--b3) !important; color-scheme: dark !important;" role="dialog" aria-modal="true"
    aria-label="منوی اصلی" @click.stop>
    {{-- Mobile Brand Header --}}
    <div class="flex justify-between items-center px-6 h-16 border-b border-base-content/10">
        <div class="flex gap-3 items-center">
            <div class="flex justify-center items-center w-10 h-10 rounded-full bg-primary/20">
                <x-icon name="o-cube" class="w-6 h-6 text-white" />
            </div>
            <h1 class="text-xl font-bold text-white">فرست</h1>
        </div>
        <button @click="$dispatch('toggle-sidebar')" aria-label="بستن منو"
            class="p-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2"
            type="button">
            <x-icon name="o-x-mark" class="w-5 h-5" />
        </button>
    </div>

    {{-- Mobile Navigation Menu - Using pre-computed menu data --}}
    <nav class="overflow-y-auto flex-1 px-4 py-4" aria-label="منوی اصلی">
        <ul class="flex flex-col gap-2" x-data="{ isRouteActive: window.isRouteActive }">
            @foreach ($navbarMenu as $menu)
                <x-frest-sidebar-menu-item :menu="$menu" />
            @endforeach
        </ul>
    </nav>
</aside>
