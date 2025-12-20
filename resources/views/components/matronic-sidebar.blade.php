@props(['navbarMenu' => []])

@php
    $navbarMenu = $navbarMenu ?? [];
@endphp

{{-- Fixed Sidebar - Left side, dark grey theme --}}
<aside x-data="{
    collapsed: localStorage.getItem('matronic_sidebar_collapsed') === 'true',
    toggle() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('matronic_sidebar_collapsed', this.collapsed.toString());
        $dispatch('sidebar-collapse', { collapsed: this.collapsed });
    }
}"
    class="matronic-sidebar fixed top-0 left-0 z-40 h-screen bg-[#1E1E2E] border-r border-gray-800 hidden lg:flex flex-col transition-all duration-300"
    x-bind:class="collapsed ? 'w-20' : 'w-[280px]'">
    <style>
        .matronic-sidebar,
        .matronic-sidebar * {
            color-scheme: dark !important;
        }

        .matronic-sidebar .menu li>*,
        .matronic-sidebar .menu li>a,
        .matronic-sidebar .menu li>button {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .matronic-sidebar .menu li:hover>*,
        .matronic-sidebar .menu li:hover>a,
        .matronic-sidebar .menu li:hover>button {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        .matronic-sidebar .menu li[data-active='true']>*,
        .matronic-sidebar .menu li.active>*,
        .matronic-sidebar .menu li[aria-current='page']>* {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: white !important;
        }

        .matronic-sidebar svg,
        .matronic-sidebar svg *,
        .matronic-sidebar [class*='icon'],
        .matronic-sidebar i {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .matronic-sidebar h1,
        .matronic-sidebar h2,
        .matronic-sidebar h3,
        .matronic-sidebar h4 {
            color: white !important;
        }
    </style>

    {{-- Top Section: Logo + Branch Selector --}}
    <div class="px-4 pt-4 pb-3 border-b border-gray-800">
        <div class="flex items-center gap-3" x-bind:class="collapsed ? 'justify-center' : ''">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/10 shrink-0">
                <span class="text-white font-bold text-lg">A</span>
            </div>
            <div x-show="!collapsed" x-transition class="flex-1 min-w-0">
                <livewire:admin.shared.branch-selector />
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="px-4 py-3 border-b border-gray-800" x-show="!collapsed" x-transition>
        <div class="relative">
            <x-icon name="o-magnifying-glass" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <input type="text" placeholder="Search..."
                class="w-full pl-10 pr-8 py-2 text-sm bg-white/5 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white/20 focus:border-transparent">
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">⌘ K</span>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex-1 overflow-y-auto px-2 py-4">
        <x-menu activate-by-route class="flex flex-col gap-1">
            @foreach ($navbarMenu ?? [] as $menu)
                @if (Arr::has($menu, 'sub_menu'))
                    @if (Arr::get($menu, 'access', true))
                        <div x-data="{ open: false }">
                            <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')"
                                class="text-white/90 hover:text-white hover:bg-white/10 rounded-lg" x-show="!collapsed">
                                @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                    @if (Arr::get($subMenu, 'access', true))
                                        <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')"
                                            :badge="Arr::get($subMenu, 'badge')" :badge-classes="Arr::get($subMenu, 'badge_classes', '')" :link="route(
                                                Arr::get($subMenu, 'route_name'),
                                                Arr::get($subMenu, 'params', []),
                                            )"
                                            class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg" />
                                    @endif
                                @endforeach
                            </x-menu-sub>
                            {{-- Icon-only when collapsed --}}
                            <div x-show="collapsed" class="relative group" x-data="{ showPopup: false }"
                                @mouseenter="showPopup = true" @mouseleave="showPopup = false">
                                <button @click="open = !open"
                                    class="w-full flex items-center justify-center p-3 rounded-lg transition-colors"
                                    x-bind:class="open || showPopup ? 'bg-white/15 text-white' :
                                        'text-white/90 hover:text-white hover:bg-white/10'">
                                    <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5" />
                                </button>
                                {{-- Submenu popup --}}
                                <div x-show="open || showPopup" @mouseenter="showPopup = true"
                                    @mouseleave="showPopup = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-x-2"
                                    x-transition:enter-end="opacity-100 translate-x-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-x-0"
                                    x-transition:leave-end="opacity-0 translate-x-2" x-cloak
                                    class="absolute left-full top-0 w-56 bg-[#1E1E2E] border border-gray-800 rounded-lg shadow-2xl z-[100] ml-2">
                                    <div class="py-1.5">
                                        @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                            @if (Arr::get($subMenu, 'access', true))
                                                <a href="{{ route(Arr::get($subMenu, 'route_name'), Arr::get($subMenu, 'params', [])) }}"
                                                    class="flex items-center gap-3 px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-md transition-all duration-150"
                                                    @click="open = false; showPopup = false">
                                                    <x-icon name="{{ Arr::get($subMenu, 'icon') }}"
                                                        class="w-4 h-4 shrink-0" />
                                                    <span
                                                        class="text-sm whitespace-nowrap">{{ Arr::get($subMenu, 'title') }}</span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                    <x-menu-separator :title="Arr::get($menu, 'title')" class="my-4 text-white/50" x-show="!collapsed" />
                    <div x-show="collapsed" class="my-4 border-t border-white/10"></div>
                @else
                    @if (Arr::get($menu, 'access', true))
                        <div>
                            <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                                :badge-classes="Arr::get($menu, 'badge_classes', '')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))"
                                class="text-white/90 hover:text-white hover:bg-white/10 rounded-lg"
                                x-show="!collapsed" />
                            {{-- Icon-only when collapsed --}}
                            <a href="{{ route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', [])) }}"
                                x-show="collapsed"
                                class="flex items-center justify-center p-3 rounded-lg transition-colors relative group text-white/90 hover:text-white hover:bg-white/10"
                                title="{{ Arr::get($menu, 'title') }}">
                                <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-5 h-5" />
                            </a>
                        </div>
                    @endif
                @endif
            @endforeach
        </x-menu>
    </nav>

    {{-- Bottom: User Avatar --}}
    <div class="px-4 py-4 border-t border-gray-800" x-show="!collapsed" x-transition>
        <div class="flex items-center gap-3">
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                    <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                </div>
                <div
                    class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-[#1E1E2E]">
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'User' }}</div>
                <div class="text-xs text-white/60 truncate">{{ auth()->user()->email ?? '' }}</div>
            </div>
        </div>
    </div>
    <div x-show="collapsed" class="px-4 py-4 border-t border-gray-800 flex justify-center">
        <div class="relative">
            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
            </div>
            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-[#1E1E2E]">
            </div>
        </div>
    </div>

    {{-- Collapse Toggle Button --}}
    <button @click="toggle()"
        class="absolute -right-3 top-20 w-6 h-6 flex items-center justify-center bg-[#1E1E2E] border-2 border-gray-800 rounded-full hover:bg-white/10 text-white transition-colors z-10 shadow-lg">
        <x-icon name="o-chevron-right" class="w-3 h-3" x-bind:class="collapsed ? 'rotate-180' : ''" />
    </button>
</aside>

{{-- Mobile Sidebar Overlay --}}
<div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-30 bg-black/50 lg:hidden" @click="sidebarOpen = false" x-cloak>
</div>

{{-- Mobile Sidebar --}}
<aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="matronic-sidebar fixed top-0 left-0 z-40 h-screen w-[280px] bg-[#1E1E2E] border-r border-gray-800 flex flex-col lg:hidden"
    @click.outside="sidebarOpen = false" x-cloak>
    {{-- Mobile Brand Header --}}
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-800">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/10">
                <span class="text-white font-bold text-lg">A</span>
            </div>
            <h1 class="text-xl font-bold text-white">Thunder AI</h1>
        </div>
        <button @click="sidebarOpen = false" class="p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-lg">
            <x-icon name="o-x-mark" class="w-5 h-5" />
        </button>
    </div>

    {{-- Mobile Navigation Menu --}}
    <nav class="flex-1 overflow-y-auto px-4 py-4">
        <x-menu activate-by-route class="flex flex-col gap-2">
            @foreach ($navbarMenu ?? [] as $menu)
                @if (Arr::has($menu, 'sub_menu'))
                    @if (Arr::get($menu, 'access', true))
                        <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')"
                            class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg">
                            @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                @if (Arr::get($subMenu, 'access', true))
                                    <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')"
                                        :badge="Arr::get($subMenu, 'badge')" :badge-classes="Arr::get($subMenu, 'badge_classes', '')" :link="route(
                                            Arr::get($subMenu, 'route_name'),
                                            Arr::get($subMenu, 'params', []),
                                        )"
                                        class="text-white/70 hover:text-white hover:bg-white/10 rounded-lg"
                                        @click="sidebarOpen = false" />
                                @endif
                            @endforeach
                        </x-menu-sub>
                    @endif
                @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                    <x-menu-separator :title="Arr::get($menu, 'title')" class="my-4 text-white/50" />
                @else
                    @if (Arr::get($menu, 'access', true))
                        <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                            :badge-classes="Arr::get($menu, 'badge_classes', '')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))"
                            class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg"
                            @click="sidebarOpen = false" />
                    @endif
                @endif
            @endforeach
        </x-menu>
    </nav>
</aside>
