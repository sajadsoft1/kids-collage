<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">
@php
    use App\Helpers\Constants;
    use App\Enums\UserTypeEnum;
    use Illuminate\Support\Arr;

    $isEmployee = auth()->user()?->type === UserTypeEnum::EMPLOYEE;

    /**
     * Check if route is active
     */
    function isRouteActive(string $routeName, array $params = [], bool $exact = false): bool
    {
        if (!$routeName || !request()->routeIs($routeName)) {
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

    // Filter modules (items with sub_menu)
    $modules = collect($navbarMenu ?? [])
        ->filter(fn($menu) => Arr::has($menu, 'sub_menu') && Arr::get($menu, 'access', true))
        ->map(function ($menu, $index) {
            return [
                'id' => 'module-' . $index,
                'key' => 'module-' . $index,
                'title' => Arr::get($menu, 'title'),
                'icon' => Arr::get($menu, 'icon', 'o-cube'),
                'sub_menu' => Arr::get($menu, 'sub_menu', []),
            ];
        })
        ->values()
        ->toArray();

    // Find active module based on current route
    $activeModuleKey = null;
    foreach ($modules as $module) {
        foreach (Arr::get($module, 'sub_menu', []) as $subMenu) {
            if (Arr::get($subMenu, 'access', true)) {
                $routeName = Arr::get($subMenu, 'route_name');
                $params = Arr::get($subMenu, 'params', []);
                $exact = Arr::get($subMenu, 'exact', false);
                if ($routeName && isRouteActive($routeName, $params, $exact)) {
                    $activeModuleKey = $module['key'];
                    break 2;
                }
            }
        }
    }

    // Set default active module
    $defaultModule = $activeModuleKey ?? (!empty($modules) ? $modules[0]['key'] : 'module-0');
@endphp

@include('components.layouts.shared.head')

<body class="bg-base-200 antialiased">

    <div x-data="{
        sidebarOpen: false,
        menuVisible: false,
        activeModule: '{{ $defaultModule }}',
        init() {
            if (window.innerWidth >= 768) {
                const saved = sessionStorage.getItem('sidebarOpen');
                this.sidebarOpen = saved !== null ? saved === 'true' : true;
            }
            this.menuVisible = this.sidebarOpen;
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            this.menuVisible = this.sidebarOpen;
            if (window.innerWidth >= 768) {
                sessionStorage.setItem('sidebarOpen', this.sidebarOpen.toString());
            }
        },
        openMenu(module) {
            this.activeModule = module;
            this.menuVisible = true;
        },
        closeMenuIfOverlay() {
            if (!this.sidebarOpen) {
                this.menuVisible = false;
            }
        }
    }" x-init="init()" class="min-h-screen flex flex-col md:flex-row">

        <!-- Mobile/Tablet Header -->
        <header
            class="md:hidden fixed top-0 right-0 left-0 z-40 h-14 bg-slate-900 flex items-center justify-between px-4">
            <button @click="toggleSidebar()"
                class="w-10 h-10 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <h1 class="text-white font-bold text-lg">پنل مدیریت</h1>
            <div
                class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white text-sm font-medium">
                JD
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 right-0 z-50 flex transition-all duration-300 top-14 md:top-0"
            :class="menuVisible ? 'w-full md:w-auto' : 'w-0 md:w-[70px]'">

            <!-- Toggle Button (Floating - Desktop Only) -->
            <button @click="toggleSidebar()"
                class="hidden md:flex absolute top-6 -left-3 z-50 w-6 h-6 bg-slate-700 hover:bg-slate-600 text-white rounded-full items-center justify-center shadow-lg transition-all"
                :class="sidebarOpen ? 'rotate-180' : ''">
                <i class="fas fa-chevron-left text-xs"></i>
            </button>

            <!-- Icon Column (Level 1) -->
            <div class="w-[70px] bg-slate-900 flex flex-col items-center py-4 gap-1 shrink-0"
                :class="menuVisible ? '' : 'hidden md:flex'">

                <!-- Modules -->
                <div class="flex-1 flex flex-col items-center gap-1 py-2 w-full px-2">
                    @foreach ($modules as $module)
                        <button @click="openMenu('{{ $module['key'] }}')"
                            class="w-11 h-11 rounded-xl flex items-center justify-center transition-all"
                            :class="activeModule === '{{ $module['key'] }}' ? 'bg-blue-600 text-white' :
                                'text-slate-400 hover:bg-slate-800 hover:text-white'"
                            title="{{ $module['title'] }}">
                            <x-icon name="{{ $module['icon'] }}" class="w-5 h-5" />
                        </button>
                    @endforeach
                </div>

                <!-- Bottom Section: Search, Notifications, Branch, Settings, Profile -->
                <div class="flex flex-col items-center gap-1 pt-4 border-t border-slate-700 w-full px-2">
                    <button @click="openMenu('search')"
                        class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                        <i class="fas fa-search text-base"></i>
                    </button>
                    <button @click="openMenu('notifications')"
                        class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white transition-all relative">
                        <i class="fas fa-bell text-base"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <button @click="openMenu('branch')"
                        class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                        <i class="fas fa-building text-base"></i>
                    </button>
                    <button @click="openMenu('settings')"
                        class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                        <i class="fas fa-cog text-base"></i>
                    </button>
                    <button @click="openMenu('profile')"
                        class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white text-sm font-medium">
                        JD
                    </button>
                </div>
            </div>

            <!-- Menu Column (Level 2) -->
            <div class="flex-1 md:w-[240px] bg-slate-800 flex flex-col transition-all duration-300 overflow-hidden"
                :class="menuVisible ? 'opacity-100' : 'opacity-0 w-0 md:w-0'">

                <!-- Module Title -->
                <div class="px-4 py-4 border-b border-slate-700">
                    <h2 class="font-semibold text-slate-200 text-sm">
                        @foreach ($modules as $module)
                            <span x-show="activeModule === '{{ $module['key'] }}'">{{ $module['title'] }}</span>
                        @endforeach
                        <span x-show="activeModule === 'search'">جستجو</span>
                        <span x-show="activeModule === 'notifications'">اعلانات</span>
                        <span x-show="activeModule === 'branch'">انتخاب شعبه</span>
                        <span x-show="activeModule === 'settings'">تنظیمات</span>
                        <span x-show="activeModule === 'profile'">پروفایل</span>
                    </h2>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-1">
                    @foreach ($modules as $module)
                        <div x-show="activeModule === '{{ $module['key'] }}'" class="space-y-1">
                            @foreach (Arr::get($module, 'sub_menu', []) as $subMenu)
                                @if (Arr::get($subMenu, 'access', true))
                                    @php
                                        $routeName = Arr::get($subMenu, 'route_name');
                                        $params = Arr::get($subMenu, 'params', []);
                                        $exact = Arr::get($subMenu, 'exact', false);
                                        $isActive = $routeName ? isRouteActive($routeName, $params, $exact) : false;
                                    @endphp
                                    <a href="{{ $routeName ? route($routeName, $params) : '#' }}"
                                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all {{ $isActive ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white' }}">
                                        <x-icon name="{{ Arr::get($subMenu, 'icon', 'o-cube') }}" class="w-5 h-5" />
                                        <span>{{ Arr::get($subMenu, 'title') }}</span>
                                        @if (Arr::get($subMenu, 'badge'))
                                            <span
                                                class="mr-auto px-2 py-0.5 text-xs font-medium rounded-full {{ Arr::get($subMenu, 'badge_classes', 'bg-blue-500/20 text-blue-400') }}">
                                                {{ Arr::get($subMenu, 'badge') }}
                                            </span>
                                        @endif
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endforeach

                    <!-- Search Panel -->
                    <div x-show="activeModule === 'search'" class="space-y-3">
                        <div class="relative">
                            <i
                                class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                            <input type="text" placeholder="جستجو در سیستم..."
                                class="w-full pr-10 pl-4 py-2.5 bg-slate-700 border-0 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <p class="text-slate-500 text-xs">جستجو در تمام ماژول‌ها...</p>
                    </div>

                    <!-- Notifications Panel -->
                    <div x-show="activeModule === 'notifications'" class="space-y-2">
                        <div class="p-3 bg-slate-700 rounded-lg">
                            <p class="text-slate-200 text-sm">فاکتور جدید ثبت شد</p>
                            <p class="text-slate-500 text-xs mt-1">۵ دقیقه پیش</p>
                        </div>
                        <div class="p-3 bg-slate-700 rounded-lg">
                            <p class="text-slate-200 text-sm">کاربر جدید عضو شد</p>
                            <p class="text-slate-500 text-xs mt-1">۱ ساعت پیش</p>
                        </div>
                        <div class="p-3 bg-slate-700 rounded-lg">
                            <p class="text-slate-200 text-sm">گزارش ماهانه آماده است</p>
                            <p class="text-slate-500 text-xs mt-1">دیروز</p>
                        </div>
                    </div>

                    <!-- Branch Panel -->
                    <div x-show="activeModule === 'branch'" class="space-y-1">
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg bg-blue-600 text-white text-sm">
                            <i class="fas fa-check w-5 text-center text-sm"></i>
                            <span>شعبه مرکزی</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm transition-all">
                            <i class="fas fa-building w-5 text-center text-sm"></i>
                            <span>شعبه شمال</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm transition-all">
                            <i class="fas fa-building w-5 text-center text-sm"></i>
                            <span>شعبه جنوب</span>
                        </a>
                    </div>

                    <!-- Settings Panel -->
                    <div x-show="activeModule === 'settings'" class="space-y-1">
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm transition-all">
                            <i class="fas fa-palette w-5 text-center text-sm"></i>
                            <span>ظاهر</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm transition-all">
                            <i class="fas fa-globe w-5 text-center text-sm"></i>
                            <span>زبان</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm transition-all">
                            <i class="fas fa-shield-alt w-5 text-center text-sm"></i>
                            <span>امنیت</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm transition-all">
                            <i class="fas fa-database w-5 text-center text-sm"></i>
                            <span>پشتیبان‌گیری</span>
                        </a>
                    </div>

                    <!-- Profile Panel -->
                    <div x-show="activeModule === 'profile'" class="space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-slate-700 rounded-lg">
                            <div
                                class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-medium">
                                JD</div>
                            <div>
                                <p class="text-slate-200 text-sm font-medium">جان دو</p>
                                <p class="text-slate-500 text-xs">مدیر سیستم</p>
                            </div>
                        </div>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm transition-all">
                            <i class="fas fa-user w-5 text-center text-sm"></i>
                            <span>ویرایش پروفایل</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-sm transition-all">
                            <i class="fas fa-key w-5 text-center text-sm"></i>
                            <span>تغییر رمز عبور</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-red-400 hover:bg-red-600 hover:text-white text-sm transition-all">
                            <i class="fas fa-sign-out-alt w-5 text-center text-sm"></i>
                            <span>خروج</span>
                        </a>
                    </div>
                </nav>

            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 transition-all duration-300 flex flex-col mt-14 md:mt-0"
            :class="sidebarOpen ? 'md:mr-[310px]' : 'md:mr-[70px]'" @click="closeMenuIfOverlay()">

            <div class="flex-1 p-4 md:p-6">

                <!-- Breadcrumb & Title -->
                <div class="mb-6">
                    <nav class="flex items-center gap-2 text-sm text-slate-400 mb-2">
                        <a href="#" class="hover:text-blue-500">خانه</a>
                        <i class="fas fa-chevron-left text-xs"></i>
                        <span>داشبورد</span>
                    </nav>
                    <h1 class="text-xl font-bold text-slate-800">داشبورد</h1>
                </div>
                {{ $slot }}
            </div>

            <!-- Footer -->
            <footer class="border-t border-slate-200 bg-white px-6 py-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-2">
                    <p class="text-xs text-slate-400">© ۱۴۰۳ پنل مدیریت</p>
                    <p class="text-xs text-slate-400">نسخه ۱.۰.۰</p>
                </div>
            </footer>
        </main>

        <!-- Mobile Overlay -->
        <div x-show="menuVisible" @click="menuVisible = false; sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-40 md:hidden" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
        </div>
    </div>

    @include('components.layouts.shared.shared')
</body>

</html>
