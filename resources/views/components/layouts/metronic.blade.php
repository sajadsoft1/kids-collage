@php
    use Illuminate\Support\Arr;

    /**
     * Check if route is active
     */
    function isRouteActive(string $routeName, array $params = [], bool $exact = false): bool
    {
        if (!request()->routeIs($routeName)) {
            return false;
        }

        if ($exact && !empty($params)) {
            $currentParams = request()->route()->parameters();
            foreach ($params as $key => $value) {
                if (!isset($currentParams[$key]) || $currentParams[$key] != $value) {
                    return false;
                }
            }
        }

        return true;
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">
@include('components.layouts.shared.head')

<style type="text/tailwindcss">
    @theme {
        --color-sidebar: #1e1e2d;
        --color-sidebar-hover: #2a2a3c;
        --color-sidebar-active: #1b84ff;
        --color-primary: #1b84ff;
        --color-primary-light: #e8f4ff;
        --color-gray-bg: #f9fafb;
    }

    .dark {
        --color-gray-bg: #0f172a;
    }

    /* Sidebar base styling */
    .metronic-sidebar {
        background-color: var(--color-sidebar) !important;
        color-scheme: dark;
    }

    /* Sidebar text colors */
    .metronic-sidebar {
        color: rgba(156, 163, 175, 1);
    }

    .metronic-sidebar .menu-text-gray {
        color: rgba(156, 163, 175, 1) !important;
    }

    .metronic-sidebar .menu-item:hover .menu-text-gray,
    .metronic-sidebar .menu-text-gray:hover {
        color: white !important;
    }

    .metronic-sidebar .menu-text-gray-secondary {
        color: rgba(107, 114, 128, 1) !important;
    }

    .metronic-sidebar .menu-text-white {
        color: white !important;
    }

    /* Sidebar hover effects */
    .metronic-sidebar .menu-item {
        transition: background-color 0.2s, color 0.2s;
    }

    .metronic-sidebar .menu-item:hover {
        background-color: var(--color-sidebar-hover) !important;
    }

    .metronic-sidebar .menu-item:hover .menu-text-gray {
        color: white !important;
    }

    .metronic-sidebar .menu-item-active {
        background-color: var(--color-sidebar-active) !important;
    }

    .metronic-sidebar .menu-item-active .menu-text-gray,
    .metronic-sidebar .menu-item-active {
        color: white !important;
    }

    /* Icon colors in sidebar */
    .metronic-sidebar svg {
        color: currentColor;
    }

    .metronic-sidebar .menu-item-active svg {
        color: white !important;
    }
</style>

@php
    $isRtl = app()->getLocale() == 'fa';
@endphp

<body class="bg-gray-bg dark:bg-gray-900 font-sans antialiased transition-colors" x-data="{
    sidebarCollapsed: localStorage.getItem('metronic_sidebar_collapsed') === 'true',
    mobileMenuOpen: false,
    toggleSidebar() {
        this.sidebarCollapsed = !this.sidebarCollapsed;
        localStorage.setItem('metronic_sidebar_collapsed', this.sidebarCollapsed.toString());
    },
    init() {
        this.$watch('sidebarCollapsed', (value) => {
            localStorage.setItem('metronic_sidebar_collapsed', value.toString());
        });
    }
}">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside
            class="metronic-sidebar fixed inset-y-0 z-50 flex flex-col transition-all duration-300 {{ $isRtl ? 'right-0' : 'left-0' }}"
            x-bind:class="[
                mobileMenuOpen ? 'translate-x-0' :
                '{{ $isRtl ? 'translate-x-full lg:translate-x-0' : '-translate-x-full lg:translate-x-0' }}',
                sidebarCollapsed ? 'w-20 lg:w-20' : 'w-[250px] lg:w-[250px]'
            ]">
            {{-- Logo --}}
            <div class="flex h-[70px] items-center transition-all duration-300"
                :class="sidebarCollapsed ? 'px-4 justify-center' : 'px-6'">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2" x-show="!sidebarCollapsed"
                    x-transition>
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg shrink-0"
                        style="background-color: var(--color-sidebar-active);">
                        <x-icon name="o-bolt" class="size-5 text-white" />
                    </div>
                    <span
                        class="text-xl font-bold text-white whitespace-nowrap">{{ config('app.name', 'Metronic') }}</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center w-full"
                    x-show="sidebarCollapsed" x-transition>
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg"
                        style="background-color: var(--color-sidebar-active);">
                        <x-icon name="o-bolt" class="size-5 text-white" />
                    </div>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-4 transition-all duration-300"
                :class="sidebarCollapsed ? 'px-2' : 'px-4'">
                @php
                    $currentSection = null;
                @endphp
                @foreach ($navbarMenu ?? [] as $menu)
                    @php
                        $hasAccess = Arr::get($menu, 'access', true);
                        $menuTitle = Arr::get($menu, 'title');
                        $menuIcon = Arr::get($menu, 'icon');
                        $menuType = Arr::get($menu, 'type');
                        $hasSubMenu = Arr::has($menu, 'sub_menu');
                    @endphp

                    @if (!$hasAccess)
                        @continue
                    @endif

                    @if ($menuType === 'seperator')
                        <div class="my-4" x-show="!sidebarCollapsed" x-transition>
                            <span
                                class="mb-2 block px-3 text-xs font-semibold uppercase tracking-wider menu-text-gray-secondary">{{ $menuTitle }}</span>
                        </div>
                    @elseif ($hasSubMenu)
                        {{-- Menu with Submenu --}}
                        <div x-data="{ open: {{ isRouteActive(Arr::get($menu, 'route_name', ''), Arr::get($menu, 'params', [])) ? 'true' : 'false' }} }" class="mb-1 relative group">
                            <button @click="open = !open"
                                class="menu-item mb-1 flex w-full items-center rounded-lg py-2.5 menu-text-gray transition-colors {{ isRouteActive(Arr::get($menu, 'route_name', ''), Arr::get($menu, 'params', [])) ? 'menu-item-active' : '' }}"
                                :class="sidebarCollapsed ? 'px-2 justify-center' : 'px-3 justify-between gap-3'"
                                :title="sidebarCollapsed ? '{{ $menuTitle }}' : ''">
                                <div class="flex items-center gap-3">
                                    <x-icon :name="$menuIcon" class="size-4 shrink-0" />
                                    <span class="text-sm font-medium whitespace-nowrap" x-show="!sidebarCollapsed"
                                        x-transition>{{ $menuTitle }}</span>
                                </div>
                                <x-icon name="o-chevron-down" class="size-3 transition-transform shrink-0"
                                    x-bind:class="open ? 'rotate-180' : ''" x-show="!sidebarCollapsed" x-transition />
                            </button>
                            {{-- Tooltip for collapsed state --}}
                            <div x-show="sidebarCollapsed"
                                class="absolute {{ $isRtl ? 'left-full ml-2' : 'right-full mr-2' }} top-0 z-50 hidden group-hover:block">
                                <div
                                    class="bg-gray-800 text-white text-sm rounded-lg px-3 py-2 whitespace-nowrap shadow-lg">
                                    {{ $menuTitle }}
                                </div>
                            </div>
                            <div x-show="open && !sidebarCollapsed" x-collapse
                                class="space-y-1 border-gray-700 {{ $isRtl ? 'border-r' : 'border-l' }}"
                                :class="sidebarCollapsed ? '' : '{{ $isRtl ? 'mr-6 pr-4' : 'ml-6 pl-4' }}'">
                                @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                    @php
                                        $subAccess = Arr::get($subMenu, 'access', true);
                                        $subTitle = Arr::get($subMenu, 'title');
                                        $subIcon = Arr::get($subMenu, 'icon');
                                        $subRoute = Arr::get($subMenu, 'route_name');
                                        $subParams = Arr::get($subMenu, 'params', []);
                                        $subExact = Arr::get($subMenu, 'exact', false);
                                        $subBadge = Arr::get($subMenu, 'badge');
                                        $isSubActive = isRouteActive($subRoute, $subParams, $subExact);
                                    @endphp
                                    @if ($subAccess)
                                        <a href="{{ route($subRoute, $subParams) }}"
                                            class="menu-item block rounded-lg py-2 text-sm transition-colors {{ $isSubActive ? 'menu-item-active menu-text-white' : 'menu-text-gray' }} relative group"
                                            :class="sidebarCollapsed ? 'px-2' : 'px-3'"
                                            :title="sidebarCollapsed ? '{{ $subTitle }}' : ''">
                                            <div class="flex items-center gap-2">
                                                @if ($subIcon)
                                                    <x-icon :name="$subIcon" class="size-3 shrink-0" />
                                                @endif
                                                <span x-show="!sidebarCollapsed"
                                                    x-transition>{{ $subTitle }}</span>
                                                @if ($subBadge)
                                                    <span
                                                        class="ml-auto rounded bg-primary px-1.5 py-0.5 text-xs font-medium text-white"
                                                        x-show="!sidebarCollapsed"
                                                        x-transition>{{ $subBadge }}</span>
                                                @endif
                                            </div>
                                            {{-- Tooltip for collapsed state --}}
                                            <div x-show="sidebarCollapsed"
                                                class="absolute {{ $isRtl ? 'left-full ml-2' : 'right-full mr-2' }} top-0 z-50 hidden group-hover:block">
                                                <div
                                                    class="bg-gray-800 text-white text-sm rounded-lg px-3 py-2 whitespace-nowrap shadow-lg">
                                                    {{ $subTitle }}
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Single Menu Item --}}
                        @php
                            $routeName = Arr::get($menu, 'route_name');
                            $params = Arr::get($menu, 'params', []);
                            $exact = Arr::get($menu, 'exact', false);
                            $isActive = isRouteActive($routeName, $params, $exact);
                            $badge = Arr::get($menu, 'badge');
                        @endphp
                        <a href="{{ route($routeName, $params) }}"
                            class="menu-item mb-1 flex items-center rounded-lg py-2.5 transition-colors {{ $isActive ? 'menu-item-active menu-text-white' : 'menu-text-gray' }} relative group"
                            :class="sidebarCollapsed ? 'px-2 justify-center' : 'px-3 gap-3'"
                            :title="sidebarCollapsed ? '{{ $menuTitle }}' : ''">
                            <x-icon :name="$menuIcon" class="size-4 shrink-0" />
                            <span class="text-sm font-medium whitespace-nowrap" x-show="!sidebarCollapsed"
                                x-transition>{{ $menuTitle }}</span>
                            {{-- Tooltip for collapsed state --}}
                            <div x-show="sidebarCollapsed"
                                class="absolute {{ $isRtl ? 'left-full ml-2' : 'right-full mr-2' }} top-0 z-50 hidden group-hover:block">
                                <div
                                    class="bg-gray-800 text-white text-sm rounded-lg px-3 py-2 whitespace-nowrap shadow-lg">
                                    {{ $menuTitle }}
                                </div>
                            </div>
                            @if ($badge)
                                <span class="ml-auto rounded bg-primary px-1.5 py-0.5 text-xs font-medium text-white"
                                    x-show="!sidebarCollapsed" x-transition>{{ $badge }}</span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </nav>

            {{-- User Profile Footer --}}
            <div class="border-t border-gray-700/50 transition-all duration-300"
                :class="sidebarCollapsed ? 'p-2' : 'p-4'">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="menu-item flex w-full items-center rounded-lg py-2.5 menu-text-gray transition-colors"
                        :class="sidebarCollapsed ? 'px-2 justify-center' : 'px-3 gap-3'">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-700">
                            @if (auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}"
                                    class="h-full w-full rounded-full object-cover" />
                            @else
                                <span class="text-sm text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="flex-1 {{ $isRtl ? 'text-right' : 'text-left' }} hidden lg:block"
                            x-show="!sidebarCollapsed" x-transition>
                            <p class="text-sm font-medium menu-text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs menu-text-gray-secondary">{{ auth()->user()->email }}</p>
                        </div>
                        <x-icon name="o-chevron-down" class="size-3 transition-transform hidden lg:block shrink-0"
                            x-bind:class="open ? 'rotate-180' : ''" x-show="!sidebarCollapsed" x-transition />
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                        class="metronic-sidebar absolute bottom-full mb-2 w-full rounded-xl border border-gray-700 py-2 shadow-lg {{ $isRtl ? 'right-0' : 'left-0' }}">
                        <a href="{{ route('admin.app.profile', ['user' => auth()->id()]) }}"
                            class="menu-item flex items-center gap-3 px-4 py-2 text-sm menu-text-gray transition-colors">
                            <x-icon name="o-user" class="size-4" />
                            {{ __('Profile') }}
                        </a>
                        <a href="{{ route('admin.setting') }}"
                            class="menu-item flex items-center gap-3 px-4 py-2 text-sm menu-text-gray transition-colors">
                            <x-icon name="o-cog-6-tooth" class="size-4" />
                            {{ __('Settings') }}
                        </a>
                        <div class="my-2 border-t border-gray-700"></div>
                        <a href="{{ route('admin.auth.logout') }}"
                            class="menu-item flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:text-red-300 transition-colors">
                            <x-icon name="o-arrow-right-start-on-rectangle" class="size-4" />
                            {{ __('Logout') }}
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Mobile Overlay --}}
        <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden">
        </div>

        {{-- Main Content Wrapper --}}
        <div class="flex flex-1 flex-col transition-all duration-300"
            x-bind:class="sidebarCollapsed ? '{{ $isRtl ? 'lg:mr-20' : 'lg:ml-20' }}' :
                '{{ $isRtl ? 'lg:mr-[250px]' : 'lg:ml-[250px]' }}'">
            {{-- Header --}}
            <header
                class="sticky top-0 z-30 flex h-[70px] items-center justify-between border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 lg:px-8 transition-colors">
                {{-- Left: Mobile Menu & Breadcrumb --}}
                <div class="flex items-center gap-4">
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 lg:hidden">
                        <x-icon name="o-bars-3" class="size-5" />
                    </button>
                    {{-- Desktop Sidebar Toggle --}}
                    <button @click="toggleSidebar()"
                        class="hidden lg:flex rounded-lg p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <x-icon name="o-bars-3" class="size-5" />
                    </button>
                    <div class="hidden items-center gap-2 text-sm md:flex">
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary">Home</a>
                        <x-icon name="o-chevron-right" class="size-3 text-gray-400" />
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $title ?? 'Dashboard' }}</span>
                    </div>
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100 md:hidden">
                        {{ $title ?? 'Dashboard' }}</h1>
                </div>

                {{-- Right: Actions --}}
                <div class="flex items-center gap-2">
                    {{-- Search --}}
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Search..."
                            class="h-10 w-64 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-100 pl-10 pr-4 text-sm focus:border-primary focus:bg-white dark:focus:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-primary"
                            @click="$dispatch('mary-search-open')" readonly>
                        <x-icon name="o-magnifying-glass"
                            class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-gray-400" />
                    </div>

                    {{-- Search Mobile --}}
                    <button @click="$dispatch('mary-search-open')"
                        class="rounded-lg p-2.5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 md:hidden">
                        <x-icon name="o-magnifying-glass" class="size-5" />
                    </button>

                    {{-- Notifications --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="relative rounded-lg p-2.5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <x-icon name="o-bell" class="size-5" />
                            <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-red-500"></span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-80 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg">
                            <div
                                class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-4 py-3">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>
                                <span class="rounded-full bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary">5
                                    new</span>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                <a href="#"
                                    class="flex gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <div
                                        class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                                        <x-icon name="o-envelope" class="size-5 text-blue-600 dark:text-blue-400" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">New message
                                            received</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">2 min ago</p>
                                    </div>
                                </a>
                                <a href="#"
                                    class="flex gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <div
                                        class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                                        <x-icon name="o-check-circle"
                                            class="size-5 text-green-600 dark:text-green-400" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Task completed
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">1 hour ago</p>
                                    </div>
                                </a>
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-700 p-2">
                                <a href="#"
                                    class="block rounded-lg py-2 text-center text-sm font-medium text-primary hover:bg-primary/5 dark:hover:bg-primary/10">View
                                    all notifications</a>
                            </div>
                        </div>
                    </div>

                    {{-- Theme Toggle --}}
                    <x-theme-toggle
                        class="rounded-lg p-2.5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700" />

                    {{-- Page Actions Slot --}}
                    @if (isset($actions))
                        <div class="mx-2 h-6 w-px bg-gray-200"></div>
                        {{ $actions }}
                    @endif
                </div>
            </header>

            {{-- Sub-header (optional) --}}
            @if (isset($subheader))
                <div
                    class="flex justify-between items-center px-4 py-2 text-sm border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 lg:px-8 transition-colors">
                    {{ $subheader }}
                </div>
            @endif

            {{-- Main Content --}}
            <main class="flex-1 p-4 lg:p-8">
                {{-- Stats Cards --}}
                @if (isset($stats))
                    <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($stats as $stat)
                            <div
                                class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ $stat['label'] ?? '' }}</p>
                                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                                            {{ $stat['value'] ?? '' }}
                                        </p>
                                    </div>
                                    <div class="flex h-12 w-12 items-center justify-center rounded-lg"
                                        style="background-color: {{ Arr::get($stat, 'color') === 'primary' ? 'var(--color-primary-light)' : 'rgba(0,0,0,0.05)' }};">
                                        <x-icon :name="$stat['icon'] ?? 'o-cube'" class="size-6"
                                            style="color: {{ Arr::get($stat, 'color') === 'primary' ? 'var(--color-primary)' : '#666' }};" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Content Card --}}
                <div
                    class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 transition-colors">
                    {{ $slot }}
                </div>
            </main>

            {{-- Footer --}}
            <footer
                class="border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-4 lg:px-8 transition-colors">
                <div
                    class="flex flex-col items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-400 md:flex-row">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Karnoweb') }}
                        v{{ config('app.version', '1.0.0') }}</p>
                    <div class="flex items-center gap-6">
                        <a href="#" class="hover:text-primary dark:hover:text-primary-400">Support</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @include('components.layouts.shared.shared')
</body>

</html>
