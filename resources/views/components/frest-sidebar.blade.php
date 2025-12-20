@props(['navbarMenu' => []])

@php
    $navbarMenu = $navbarMenu ?? [];
@endphp

{{-- Fixed Sidebar - Semi-dark theme with collapsible (always dark mode) --}}
<aside x-data="{
    collapsed: localStorage.getItem('frest_sidebar_collapsed') === 'true',
    toggle() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('frest_sidebar_collapsed', this.collapsed.toString());
        $dispatch('sidebar-collapse', { collapsed: this.collapsed });
    }
}"
    class="frest-sidebar-dark fixed top-0 right-0 z-40 h-screen bg-[#2C3040] border-l border-[#3A3F52] flex flex-col hidden lg:flex transition-all duration-300"
    :class="collapsed ? 'w-20' : 'w-72'" dir="rtl"
    style="background-color: #2C3040 !important; color-scheme: dark !important;">
    <style>
        /* Force dark mode for sidebar and all children */
        .frest-sidebar-dark,
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
        .frest-sidebar-dark [x-show] {
            /* Ensure popup appears above other elements */
        }

        /* Collapsed sidebar popup styling */
        .frest-sidebar-dark .absolute.left-full {
            min-width: 14rem;
            max-width: 14rem;
            transform-origin: left center;
        }

        /* Popup submenu item hover states */
        .frest-sidebar-dark .absolute.left-full a {
            margin: 0.125rem 0.25rem;
        }

        .frest-sidebar-dark .absolute.left-full a:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        /* Active state for popup items */
        .frest-sidebar-dark .absolute.left-full a[aria-current="page"],
        .frest-sidebar-dark .absolute.left-full a[data-active="true"] {
            background-color: var(--color-primary) !important;
            color: white !important;
        }
    </style>
    {{-- Brand Header with Toggle --}}
    <div class="flex items-center justify-between h-16 px-4 border-b border-[#3A3F52]">
        <div class="flex items-center gap-3 w-full" :class="collapsed ? 'justify-center' : ''">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/20 shrink-0">
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
        <button @click="toggle()"
            class="absolute left-0 top-16 -translate-x-1/2 w-8 h-8 flex items-center justify-center bg-[#2C3040] border-2 border-[#3A3F52] rounded-full hover:bg-white/10 text-white transition-colors z-10"
            :class="collapsed ? '' : 'rotate-180'">
            <x-icon name="o-chevron-left" class="w-4 h-4" />
        </button>
    </div>

    {{-- Navigation Menu - Pure Tailwind + Alpine.js --}}
    <nav class="flex-1 overflow-y-auto px-2 py-4 no-scrollbar">
        <ul class="flex flex-col gap-1" x-data="{
            isRouteActive(url, exact = false) {
                const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
                const targetUrl = new URL(url, window.location.origin);
                let targetPath = targetUrl.pathname.replace(/\/+$/, '') || '/';
        
                if (exact) {
                    return currentPath === targetPath;
                }
                if (currentPath === targetPath) return true;
                if (targetPath === '/') return false;
                return currentPath.startsWith(targetPath + '/') || currentPath === targetPath;
            }
        }">
            @foreach ($navbarMenu ?? [] as $menu)
                @if (Arr::has($menu, 'sub_menu'))
                    {{-- Menu with Submenu --}}
                    @if (Arr::get($menu, 'access', true))
                        @php
                            $menuUrl = '#';
                            $menuRouteName = Arr::get($menu, 'route_name');
                            if (!empty($menuRouteName)) {
                                try {
                                    $menuUrl = route($menuRouteName, Arr::get($menu, 'params', []));
                                } catch (\Exception $e) {
                                    $menuUrl = '#';
                                }
                            }
                            $hasActiveSubmenu = collect(Arr::get($menu, 'sub_menu', []))->contains(function ($subMenu) {
                                if (!Arr::get($subMenu, 'access', true)) {
                                    return false;
                                }
                                $subRouteName = Arr::get($subMenu, 'route_name');
                                if (empty($subRouteName)) {
                                    return false;
                                }
                                try {
                                    $subUrl = route($subRouteName, Arr::get($subMenu, 'params', []));
                                    $currentPath = request()->path();
                                    $subPath = parse_url($subUrl, PHP_URL_PATH);
                                    $exact = Arr::get($subMenu, 'exact', false);
                                    if ($exact) {
                                        return $currentPath === ltrim($subPath, '/') ||
                                            $currentPath === ltrim($subPath, '/') . '/';
                                    }
                                    return str_starts_with($currentPath, ltrim($subPath, '/'));
                                } catch (\Exception $e) {
                                    return false;
                                }
                            });
                        @endphp
                        <li x-data="{
                            submenuOpen: {{ $hasActiveSubmenu ? 'true' : 'false' }},
                            menuUrl: '{{ $menuUrl }}',
                            init() {
                                // Auto-open submenu if any child is active
                                this.submenuOpen = {{ $hasActiveSubmenu ? 'true' : 'false' }};
                            }
                        }" class="relative">
                            {{-- Expanded View --}}
                            <div x-show="!collapsed" class="space-y-1">
                                <button @click="submenuOpen = !submenuOpen"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors text-white/90 hover:text-white hover:bg-white/10"
                                    :class="isRouteActive(menuUrl) || submenuOpen ||
                                        {{ $hasActiveSubmenu ? 'true' : 'false' }} ? 'bg-white/5' : ''">
                                    <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5 shrink-0 text-white" />
                                    <span class="flex-1 text-right text-white">{{ Arr::get($menu, 'title') }}</span>
                                    @if (Arr::get($menu, 'badge'))
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium rounded-full {{ Arr::get($menu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                                            {{ Arr::get($menu, 'badge') }}
                                        </span>
                                    @endif
                                    <x-icon name="o-chevron-down"
                                        class="w-4 h-4 text-white transition-transform duration-200"
                                        x-bind:class="submenuOpen ? 'rotate-180' : ''" />
                                </button>
                                {{-- Submenu Items --}}
                                <div x-show="submenuOpen" x-collapse class="mr-6 mt-1 space-y-0.5">
                                    @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                        @if (Arr::get($subMenu, 'access', true))
                                            @php
                                                $subMenuUrl = '#';
                                                $subRouteName = Arr::get($subMenu, 'route_name');
                                                if (!empty($subRouteName)) {
                                                    try {
                                                        $subMenuUrl = route(
                                                            $subRouteName,
                                                            Arr::get($subMenu, 'params', []),
                                                        );
                                                    } catch (\Exception $e) {
                                                        $subMenuUrl = '#';
                                                    }
                                                }
                                                $isSubActive = false;
                                                try {
                                                    if ($subMenuUrl !== '#') {
                                                        $currentPath = request()->path();
                                                        $subPath = parse_url($subMenuUrl, PHP_URL_PATH);
                                                        $exact = Arr::get($subMenu, 'exact', false);
                                                        if ($exact) {
                                                            $isSubActive =
                                                                $currentPath === ltrim($subPath, '/') ||
                                                                $currentPath === ltrim($subPath, '/') . '/';
                                                        } else {
                                                            $isSubActive = str_starts_with(
                                                                $currentPath,
                                                                ltrim($subPath, '/'),
                                                            );
                                                        }
                                                    }
                                                } catch (\Exception $e) {
                                                    $isSubActive = false;
                                                }
                                            @endphp
                                            <a href="{{ $subMenuUrl }}"
                                                class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-white/80 hover:text-white hover:bg-white/10"
                                                :class="isRouteActive('{{ $subMenuUrl }}',
                                                        {{ Arr::get($subMenu, 'exact', false) ? 'true' : 'false' }}) ||
                                                    {{ $isSubActive ? 'true' : 'false' }} ? 'bg-primary text-white' :
                                                    ''"
                                                wire:navigate>
                                                <x-icon name="{{ Arr::get($subMenu, 'icon') }}"
                                                    class="w-4 h-4 shrink-0" />
                                                <span
                                                    class="flex-1 text-right text-sm">{{ Arr::get($subMenu, 'title') }}</span>
                                                @if (Arr::get($subMenu, 'badge'))
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-medium rounded-full {{ Arr::get($subMenu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                                                        {{ Arr::get($subMenu, 'badge') }}
                                                    </span>
                                                @endif
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- Collapsed View - Icon Only with Popup --}}
                            <div x-show="collapsed" @click.outside="submenuOpen = false" class="relative">
                                <button @click.stop="submenuOpen = !submenuOpen"
                                    class="w-full flex items-center justify-center p-3 rounded-lg transition-colors"
                                    :class="submenuOpen || {{ $hasActiveSubmenu ? 'true' : 'false' }} ?
                                        'bg-primary text-white' : 'text-white/90 hover:text-white hover:bg-white/10'">
                                    <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5" />
                                </button>
                                {{-- Popup Submenu --}}
                                <div x-show="submenuOpen" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-x-2"
                                    x-transition:enter-end="opacity-100 translate-x-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-x-0"
                                    x-transition:leave-end="opacity-0 -translate-x-2" x-cloak @click.stop
                                    class="absolute left-full top-0 w-56 bg-[#2C3040] border border-[#3A3F52] rounded-lg shadow-2xl z-[100]"
                                    style="margin-left: 0.5rem;">
                                    <div class="py-1.5">
                                        @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                            @if (Arr::get($subMenu, 'access', true))
                                                @php
                                                    $subMenuUrl = '#';
                                                    $subRouteName = Arr::get($subMenu, 'route_name');
                                                    if (!empty($subRouteName)) {
                                                        try {
                                                            $subMenuUrl = route(
                                                                $subRouteName,
                                                                Arr::get($subMenu, 'params', []),
                                                            );
                                                        } catch (\Exception $e) {
                                                            $subMenuUrl = '#';
                                                        }
                                                    }
                                                    $isSubActive = false;
                                                    try {
                                                        if ($subMenuUrl !== '#') {
                                                            $currentPath = request()->path();
                                                            $subPath = parse_url($subMenuUrl, PHP_URL_PATH);
                                                            $exact = Arr::get($subMenu, 'exact', false);
                                                            if ($exact) {
                                                                $isSubActive =
                                                                    $currentPath === ltrim($subPath, '/') ||
                                                                    $currentPath === ltrim($subPath, '/') . '/';
                                                            } else {
                                                                $isSubActive = str_starts_with(
                                                                    $currentPath,
                                                                    ltrim($subPath, '/'),
                                                                );
                                                            }
                                                        }
                                                    } catch (\Exception $e) {
                                                        $isSubActive = false;
                                                    }
                                                @endphp
                                                <a href="{{ $subMenuUrl }}"
                                                    class="flex items-center gap-3 px-4 py-2.5 mx-1 text-white/80 hover:text-white hover:bg-white/10 rounded-md transition-all duration-150"
                                                    :class="isRouteActive('{{ $subMenuUrl }}',
                                                            {{ Arr::get($subMenu, 'exact', false) ? 'true' : 'false' }}
                                                        ) || {{ $isSubActive ? 'true' : 'false' }} ?
                                                        'bg-primary text-white' : ''"
                                                    @click="submenuOpen = false" wire:navigate>
                                                    <x-icon name="{{ Arr::get($subMenu, 'icon') }}"
                                                        class="w-4 h-4 shrink-0" />
                                                    <span
                                                        class="text-sm whitespace-nowrap">{{ Arr::get($subMenu, 'title') }}</span>
                                                    @if (Arr::get($subMenu, 'badge'))
                                                        <span
                                                            class="px-2 py-0.5 text-xs font-medium rounded-full {{ Arr::get($subMenu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                                                            {{ Arr::get($subMenu, 'badge') }}
                                                        </span>
                                                    @endif
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                    {{-- Separator --}}
                    @if (Arr::get($menu, 'access', true))
                        <li x-show="!collapsed" class="my-4 px-3">
                            <div class="h-px bg-white/10"></div>
                            @if (Arr::get($menu, 'title'))
                                <span class="text-xs text-white/50 mt-2 block">{{ Arr::get($menu, 'title') }}</span>
                            @endif
                        </li>
                        <li x-show="collapsed" class="my-4">
                            <div class="h-px bg-white/10"></div>
                        </li>
                    @endif
                @else
                    {{-- Single Menu Item --}}
                    @if (Arr::get($menu, 'access', true))
                        @php
                            $menuUrl = '#';
                            $menuRouteName = Arr::get($menu, 'route_name');
                            if (!empty($menuRouteName)) {
                                try {
                                    $menuUrl = route($menuRouteName, Arr::get($menu, 'params', []));
                                } catch (\Exception $e) {
                                    $menuUrl = '#';
                                }
                            }
                            $isActive = false;
                            try {
                                if ($menuUrl !== '#') {
                                    $currentPath = request()->path();
                                    $menuPath = parse_url($menuUrl, PHP_URL_PATH);
                                    $exact = Arr::get($menu, 'exact', false);
                                    if ($exact) {
                                        $isActive =
                                            $currentPath === ltrim($menuPath, '/') ||
                                            $currentPath === ltrim($menuPath, '/') . '/';
                                    } else {
                                        $isActive = str_starts_with($currentPath, ltrim($menuPath, '/'));
                                    }
                                }
                            } catch (\Exception $e) {
                                $isActive = false;
                            }
                        @endphp
                        <li>
                            {{-- Expanded View --}}
                            <a href="{{ $menuUrl }}" x-show="!collapsed"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors text-white/90 hover:text-white hover:bg-white/10"
                                :class="isRouteActive('{{ $menuUrl }}',
                                        {{ Arr::get($menu, 'exact', false) ? 'true' : 'false' }}) ||
                                    {{ $isActive ? 'true' : 'false' }} ? 'bg-primary text-white' : ''"
                                wire:navigate>
                                <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5 shrink-0" />
                                <span class="flex-1 text-right">{{ Arr::get($menu, 'title') }}</span>
                                @if (Arr::get($menu, 'badge'))
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium rounded-full {{ Arr::get($menu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                                        {{ Arr::get($menu, 'badge') }}
                                    </span>
                                @endif
                            </a>
                            {{-- Collapsed View - Icon Only --}}
                            <a href="{{ $menuUrl }}" x-show="collapsed"
                                class="flex items-center justify-center p-3 rounded-lg transition-colors"
                                :class="isRouteActive('{{ $menuUrl }}',
                                        {{ Arr::get($menu, 'exact', false) ? 'true' : 'false' }}) ||
                                    {{ $isActive ? 'true' : 'false' }} ? 'bg-primary text-white' :
                                    'text-white/90 hover:text-white hover:bg-white/10'"
                                title="{{ Arr::get($menu, 'title') }}" wire:navigate>
                                <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5" />
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </nav>
</aside>

{{-- Mobile Sidebar Overlay --}}
<div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-30 bg-black/50 lg:hidden" @click="sidebarOpen = false" x-cloak>
</div>

{{-- Mobile Sidebar --}}
<aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="translate-x-full"
    class="frest-sidebar-dark fixed top-0 right-0 z-40 h-screen w-72 bg-[#2C3040] border-l border-[#3A3F52] flex flex-col lg:hidden"
    dir="rtl" @click.outside="sidebarOpen = false" x-cloak
    style="background-color: #2C3040 !important; color-scheme: dark !important;">
    {{-- Mobile Brand Header --}}
    <div class="flex items-center justify-between h-16 px-6 border-b border-[#3A3F52]">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/20">
                <x-icon name="o-cube" class="w-6 h-6 text-white" />
            </div>
            <h1 class="text-xl font-bold text-white">فرست</h1>
        </div>
        <button @click="sidebarOpen = false" class="p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-lg">
            <x-icon name="o-x-mark" class="w-5 h-5" />
        </button>
    </div>

    {{-- Mobile Navigation Menu - Pure Tailwind + Alpine.js --}}
    <nav class="flex-1 overflow-y-auto px-4 py-4">
        <ul class="flex flex-col gap-2" x-data="{
            isRouteActive(url, exact = false) {
                const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
                const targetUrl = new URL(url, window.location.origin);
                let targetPath = targetUrl.pathname.replace(/\/+$/, '') || '/';
        
                if (exact) {
                    return currentPath === targetPath;
                }
                if (currentPath === targetPath) return true;
                if (targetPath === '/') return false;
                return currentPath.startsWith(targetPath + '/') || currentPath === targetPath;
            }
        }">
            @foreach ($navbarMenu ?? [] as $menu)
                @if (Arr::has($menu, 'sub_menu'))
                    {{-- Menu with Submenu --}}
                    @if (Arr::get($menu, 'access', true))
                        @php
                            $hasActiveSubmenu = collect(Arr::get($menu, 'sub_menu', []))->contains(function ($subMenu) {
                                if (!Arr::get($subMenu, 'access', true)) {
                                    return false;
                                }
                                $subRouteName = Arr::get($subMenu, 'route_name');
                                if (empty($subRouteName)) {
                                    return false;
                                }
                                try {
                                    $subUrl = route($subRouteName, Arr::get($subMenu, 'params', []));
                                    $currentPath = request()->path();
                                    $subPath = parse_url($subUrl, PHP_URL_PATH);
                                    $exact = Arr::get($subMenu, 'exact', false);
                                    if ($exact) {
                                        return $currentPath === ltrim($subPath, '/') ||
                                            $currentPath === ltrim($subPath, '/') . '/';
                                    }
                                    return str_starts_with($currentPath, ltrim($subPath, '/'));
                                } catch (\Exception $e) {
                                    return false;
                                }
                            });
                        @endphp
                        <li x-data="{ submenuOpen: {{ $hasActiveSubmenu ? 'true' : 'false' }} }">
                            <button @click="submenuOpen = !submenuOpen"
                                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors text-white/80 hover:text-white hover:bg-white/10"
                                :class="submenuOpen || {{ $hasActiveSubmenu ? 'true' : 'false' }} ? 'bg-white/5' : ''">
                                <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5 shrink-0" />
                                <span class="flex-1 text-right">{{ Arr::get($menu, 'title') }}</span>
                                @if (Arr::get($menu, 'badge'))
                                    <span
                                        class="px-2 py-0.5 text-xs rounded-full {{ Arr::get($menu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                                        {{ Arr::get($menu, 'badge') }}
                                    </span>
                                @endif
                                <x-icon name="o-chevron-down" class="w-4 h-4 transition-transform duration-200"
                                    x-bind:class="submenuOpen ? 'rotate-180' : ''" />
                            </button>
                            {{-- Submenu Items --}}
                            <div x-show="submenuOpen" x-collapse class="mr-6 mt-1 space-y-1">
                                @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                    @if (Arr::get($subMenu, 'access', true))
                                        @php
                                            $subMenuUrl = '#';
                                            $subRouteName = Arr::get($subMenu, 'route_name');
                                            if (!empty($subRouteName)) {
                                                try {
                                                    $subMenuUrl = route(
                                                        $subRouteName,
                                                        Arr::get($subMenu, 'params', []),
                                                    );
                                                } catch (\Exception $e) {
                                                    $subMenuUrl = '#';
                                                }
                                            }
                                            $isSubActive = false;
                                            try {
                                                if ($subMenuUrl !== '#') {
                                                    $currentPath = request()->path();
                                                    $subPath = parse_url($subMenuUrl, PHP_URL_PATH);
                                                    $exact = Arr::get($subMenu, 'exact', false);
                                                    if ($exact) {
                                                        $isSubActive =
                                                            $currentPath === ltrim($subPath, '/') ||
                                                            $currentPath === ltrim($subPath, '/') . '/';
                                                    } else {
                                                        $isSubActive = str_starts_with(
                                                            $currentPath,
                                                            ltrim($subPath, '/'),
                                                        );
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                $isSubActive = false;
                                            }
                                        @endphp
                                        <a href="{{ $subMenuUrl }}"
                                            class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-white/70 hover:text-white hover:bg-white/10"
                                            :class="isRouteActive('{{ $subMenuUrl }}',
                                                    {{ Arr::get($subMenu, 'exact', false) ? 'true' : 'false' }}) ||
                                                {{ $isSubActive ? 'true' : 'false' }} ? 'bg-primary text-white' : ''"
                                            @click="sidebarOpen = false" wire:navigate>
                                            <x-icon name="{{ Arr::get($subMenu, 'icon') }}"
                                                class="w-4 h-4 shrink-0" />
                                            <span
                                                class="flex-1 text-right text-sm">{{ Arr::get($subMenu, 'title') }}</span>
                                            @if (Arr::get($subMenu, 'badge'))
                                                <span
                                                    class="px-2 py-0.5 text-xs rounded-full {{ Arr::get($subMenu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                                                    {{ Arr::get($subMenu, 'badge') }}
                                                </span>
                                            @endif
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </li>
                    @endif
                @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                    {{-- Separator --}}
                    @if (Arr::get($menu, 'access', true))
                        <li class="my-4 px-3">
                            <div class="h-px bg-white/10"></div>
                            @if (Arr::get($menu, 'title'))
                                <span class="text-xs text-white/50 mt-2 block">{{ Arr::get($menu, 'title') }}</span>
                            @endif
                        </li>
                    @endif
                @else
                    {{-- Single Menu Item --}}
                    @if (Arr::get($menu, 'access', true))
                        @php
                            $menuUrl = '#';
                            $menuRouteName = Arr::get($menu, 'route_name');
                            if (!empty($menuRouteName)) {
                                try {
                                    $menuUrl = route($menuRouteName, Arr::get($menu, 'params', []));
                                } catch (\Exception $e) {
                                    $menuUrl = '#';
                                }
                            }
                            $isActive = false;
                            try {
                                if ($menuUrl !== '#') {
                                    $currentPath = request()->path();
                                    $menuPath = parse_url($menuUrl, PHP_URL_PATH);
                                    $exact = Arr::get($menu, 'exact', false);
                                    if ($exact) {
                                        $isActive =
                                            $currentPath === ltrim($menuPath, '/') ||
                                            $currentPath === ltrim($menuPath, '/') . '/';
                                    } else {
                                        $isActive = str_starts_with($currentPath, ltrim($menuPath, '/'));
                                    }
                                }
                            } catch (\Exception $e) {
                                $isActive = false;
                            }
                        @endphp
                        <li>
                            <a href="{{ $menuUrl }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors text-white/80 hover:text-white hover:bg-white/10"
                                :class="isRouteActive('{{ $menuUrl }}',
                                        {{ Arr::get($menu, 'exact', false) ? 'true' : 'false' }}) ||
                                    {{ $isActive ? 'true' : 'false' }} ? 'bg-primary text-white' : ''"
                                @click="sidebarOpen = false" wire:navigate>
                                <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5 shrink-0" />
                                <span class="flex-1 text-right">{{ Arr::get($menu, 'title') }}</span>
                                @if (Arr::get($menu, 'badge'))
                                    <span
                                        class="px-2 py-0.5 text-xs rounded-full {{ Arr::get($menu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                                        {{ Arr::get($menu, 'badge') }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </nav>
</aside>
