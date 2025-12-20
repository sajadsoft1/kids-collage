@php
    use Illuminate\Support\Arr;

    /**
     * Check if route is active
     */
    if (!function_exists('isRouteActive')) {
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
    }
@endphp

<div x-data="{}" x-init="$store.sidebar.init()"
    class="flex fixed relative inset-y-0 right-0 top-14 z-50 transition-all duration-300 md:top-0"
    :class="$store.sidebar.menuVisible ? 'w-full md:w-auto' : 'w-0 md:w-[70px]'">

    <!-- Toggle Button (Floating - Desktop Only) - Positioned at left side of Level 2 when open, Level 1 when closed -->
    <button @click="$store.sidebar.toggleSidebar()" x-show="!$store.sidebar.isDirectLinkActive"
        class="hidden md:flex absolute top-6 z-[60] w-6 h-6 bg-slate-700 hover:bg-slate-600 text-white rounded-full items-center justify-center shadow-lg transition-all"
        :class="$store.sidebar.menuVisible && $store.sidebar.activeModule !== '' ?
            ($store.sidebar.sidebarOpen ? 'right-[240px]' : 'right-[310px]') :
            'right-[70px]'">
        <i class="text-xs fas fa-chevron-left" :class="$store.sidebar.sidebarOpen ? 'rotate-180' : ''"></i>
    </button>

    <!-- Icon Column (Level 1) -->
    <div class="w-[70px] bg-slate-900 flex flex-col items-center py-4 gap-1 shrink-0"
        :class="$store.sidebar.menuVisible ? '' : 'hidden md:flex'">

        <!-- Modules -->
        <div class="flex flex-col flex-1 gap-1 items-center px-2 py-2 w-full">
            @foreach ($modules as $module)
                <x-admin.sidebar.module-icon :module="$module" />
            @endforeach
        </div>

        <!-- Bottom Section: Search, Notifications, Branch, Settings, Profile -->
        <div class="flex flex-col gap-1 items-center px-2 pt-4 w-full border-t border-slate-700">
            <button @click="$store.sidebar.openMenu('search')"
                class="flex justify-center items-center w-11 h-11 rounded-xl transition-all text-slate-400 hover:bg-slate-800 hover:text-white">
                <i class="text-base fas fa-search"></i>
            </button>
            <button @click="$store.sidebar.openMenu('notifications')"
                class="flex relative justify-center items-center w-11 h-11 rounded-xl transition-all text-slate-400 hover:bg-slate-800 hover:text-white">
                <i class="text-base fas fa-bell"></i>
                <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            <button @click="$store.sidebar.openMenu('branch')"
                class="flex justify-center items-center w-11 h-11 rounded-xl transition-all text-slate-400 hover:bg-slate-800 hover:text-white">
                <i class="text-base fas fa-building"></i>
            </button>
            <button @click="$store.sidebar.openMenu('settings')"
                class="flex justify-center items-center w-11 h-11 rounded-xl transition-all text-slate-400 hover:bg-slate-800 hover:text-white">
                <i class="text-base fas fa-cog"></i>
            </button>
            <x-theme-toggle />
            <button @click="$store.sidebar.openMenu('profile')"
                class="flex justify-center items-center w-10 h-10 text-sm font-medium text-white bg-blue-600 rounded-xl">
                JD
            </button>
        </div>
    </div>

    <!-- Menu Column (Level 2) -->
    <div class="flex-1 md:w-[240px] bg-slate-800 flex flex-col transition-all duration-300 overflow-hidden"
        x-show="$store.sidebar.menuVisible && $store.sidebar.activeModule !== ''"
        :class="$store.sidebar.menuVisible && $store.sidebar.activeModule !== '' ?
            ($store.sidebar.sidebarOpen ? 'opacity-100' :
                'opacity-100 absolute right-[70px] top-0 bottom-0 z-[55] shadow-lg') :
            'opacity-0 w-0 md:w-0'">

        <!-- Module Title -->
        <div class="relative px-4 py-4 border-b border-slate-700">
            <h2 class="text-sm font-semibold text-slate-200">
                @foreach ($modules as $module)
                    @if (!Arr::get($module, 'is_direct_link', false))
                        <span
                            x-show="$store.sidebar.activeModule === '{{ $module['key'] }}'">{{ $module['title'] }}</span>
                    @endif
                @endforeach
                <span x-show="$store.sidebar.activeModule === 'search'">جستجو</span>
                <span x-show="$store.sidebar.activeModule === 'notifications'">اعلانات</span>
                <span x-show="$store.sidebar.activeModule === 'branch'">انتخاب شعبه</span>
                <span x-show="$store.sidebar.activeModule === 'settings'">تنظیمات</span>
                <span x-show="$store.sidebar.activeModule === 'profile'">پروفایل</span>
            </h2>
        </div>

        <!-- Navigation -->
        <nav class="overflow-y-auto flex-1 px-3 py-2 space-y-1">
            @foreach ($modules as $module)
                <x-admin.sidebar.module-menu :module="$module" />
            @endforeach

            <!-- Search Panel -->
            <div x-show="$store.sidebar.activeModule === 'search'" class="space-y-3">
                <div class="relative">
                    <i class="absolute right-3 top-1/2 text-sm -translate-y-1/2 fas fa-search text-slate-500"></i>
                    <input type="text" placeholder="جستجو در سیستم..."
                        class="py-2.5 pr-10 pl-4 w-full text-sm rounded-lg border-0 bg-slate-700 text-slate-200 placeholder-slate-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </div>
                <p class="text-xs text-slate-500">جستجو در تمام ماژول‌ها...</p>
            </div>

            <!-- Notifications Panel -->
            <div x-show="$store.sidebar.activeModule === 'notifications'" class="space-y-2">
                <div class="p-3 rounded-lg bg-slate-700">
                    <p class="text-sm text-slate-200">فاکتور جدید ثبت شد</p>
                    <p class="mt-1 text-xs text-slate-500">۵ دقیقه پیش</p>
                </div>
                <div class="p-3 rounded-lg bg-slate-700">
                    <p class="text-sm text-slate-200">کاربر جدید عضو شد</p>
                    <p class="mt-1 text-xs text-slate-500">۱ ساعت پیش</p>
                </div>
                <div class="p-3 rounded-lg bg-slate-700">
                    <p class="text-sm text-slate-200">گزارش ماهانه آماده است</p>
                    <p class="mt-1 text-xs text-slate-500">دیروز</p>
                </div>
            </div>

            <!-- Branch Panel -->
            <div x-show="$store.sidebar.activeModule === 'branch'" class="space-y-1">
                <a href="#" class="flex gap-3 items-center px-3 py-2 text-sm text-white bg-blue-600 rounded-lg">
                    <i class="w-5 text-sm text-center fas fa-check"></i>
                    <span>شعبه مرکزی</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2 text-sm rounded-lg transition-all text-slate-400 hover:bg-slate-700 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-building"></i>
                    <span>شعبه شمال</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2 text-sm rounded-lg transition-all text-slate-400 hover:bg-slate-700 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-building"></i>
                    <span>شعبه جنوب</span>
                </a>
            </div>

            <!-- Settings Panel -->
            <div x-show="$store.sidebar.activeModule === 'settings'" class="space-y-1">
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2 text-sm rounded-lg transition-all text-slate-400 hover:bg-slate-700 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-palette"></i>
                    <span>ظاهر</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2 text-sm rounded-lg transition-all text-slate-400 hover:bg-slate-700 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-globe"></i>
                    <span>زبان</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2 text-sm rounded-lg transition-all text-slate-400 hover:bg-slate-700 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-shield-alt"></i>
                    <span>امنیت</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2 text-sm rounded-lg transition-all text-slate-400 hover:bg-slate-700 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-database"></i>
                    <span>پشتیبان‌گیری</span>
                </a>
            </div>

            <!-- Profile Panel -->
            <div x-show="$store.sidebar.activeModule === 'profile'" class="space-y-3">
                <div class="flex gap-3 items-center p-3 rounded-lg bg-slate-700">
                    <div
                        class="flex justify-center items-center w-12 h-12 font-medium text-white bg-blue-600 rounded-full">
                        JD
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-200">جان دو</p>
                        <p class="text-xs text-slate-500">مدیر سیستم</p>
                    </div>
                </div>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2 text-sm rounded-lg transition-all text-slate-400 hover:bg-slate-700 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-user"></i>
                    <span>ویرایش پروفایل</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2 text-sm rounded-lg transition-all text-slate-400 hover:bg-slate-700 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-key"></i>
                    <span>تغییر رمز عبور</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2 text-sm text-red-400 rounded-lg transition-all hover:bg-red-600 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-sign-out-alt"></i>
                    <span>خروج</span>
                </a>
            </div>
        </nav>
    </div>
</div>

@push('scripts')
    <script>
        // Initialize Alpine store for sidebar state (persistent across page navigations)
        function initSidebarStore() {
            if (typeof Alpine === 'undefined') {
                document.addEventListener('alpine:init', initSidebarStore);
                return;
            }

            if (!Alpine.store('sidebar')) {
                // Load sidebarOpen from sessionStorage
                let savedSidebarOpen = {{ $initialSidebarOpen ? 'true' : 'false' }};
                try {
                    const saved = sessionStorage.getItem('sidebarOpen');
                    if (saved !== null) {
                        savedSidebarOpen = saved === 'true';
                    }
                } catch (e) {
                    // sessionStorage not available
                }

                Alpine.store('sidebar', {
                    get sidebarOpen() {
                        return this._sidebarOpen;
                    },
                    set sidebarOpen(value) {
                        this._sidebarOpen = value;
                        try {
                            sessionStorage.setItem('sidebarOpen', value.toString());
                        } catch (e) {
                            // sessionStorage not available
                        }
                    },
                    menuVisible: false,
                    activeModule: '{{ $defaultModule }}',
                    isDirectLinkActive: {{ $isDirectLinkActive ? 'true' : 'false' }},
                    init() {
                        // Initialize _sidebarOpen
                        this._sidebarOpen = savedSidebarOpen;
                        if (window.innerWidth >= 768 && !this.isDirectLinkActive) {
                            // Use saved value
                        } else {
                            this.sidebarOpen = false;
                        }
                        // menuVisible follows sidebarOpen state initially
                        this.menuVisible = this.sidebarOpen;
                    },
                    toggleSidebar() {
                        if (this.isDirectLinkActive) {
                            return; // Don't allow toggling when direct link is active
                        }
                        this.sidebarOpen = !this.sidebarOpen;
                        // If opening sidebar and no activeModule, set default module
                        if (this.sidebarOpen && this.activeModule === '') {
                            this.activeModule = '{{ $defaultModule }}';
                        }
                        // menuVisible follows sidebarOpen state
                        this.menuVisible = this.sidebarOpen;
                    },
                    openMenu(module) {
                        this.activeModule = module;
                        this.isDirectLinkActive = false;
                        // Show level 2 menu - if sidebar is closed, show as overlay
                        // If sidebar is open, show normally
                        this.menuVisible = true;
                    },
                    closeMenu() {
                        if (!this.sidebarOpen) {
                            this.menuVisible = false;
                        }
                    },
                    resetActiveModule() {
                        // Reset active module and close level 2 menu when clicking direct link
                        this.activeModule = '';
                        this.menuVisible = false;
                        this.sidebarOpen = false;
                        this.isDirectLinkActive = true;
                    },
                    closeMenuIfOverlay() {
                        // Close level 2 menu when clicking outside, but only if sidebar is closed
                        // If sidebar is open, keep the menu visible
                        if (!this.sidebarOpen) {
                            this.menuVisible = false;
                            this.activeModule = '';
                        }
                    }
                });
            } else {
                // Update store values if they changed (e.g., after navigation)
                const store = Alpine.store('sidebar');
                const previousActiveModule = store.activeModule;
                store.activeModule = '{{ $defaultModule }}';
                store.isDirectLinkActive = {{ $isDirectLinkActive ? 'true' : 'false' }};

                // If sidebar is open and we have an active module, keep menu visible
                // Otherwise, reset menu visibility based on new state
                if (store.sidebarOpen && store.activeModule !== '') {
                    store.menuVisible = true;
                } else if (!store.sidebarOpen && store.activeModule !== '') {
                    // Keep menu visible as overlay when sidebar is closed
                    store.menuVisible = true;
                } else {
                    store.menuVisible = false;
                }
            }
        }

        // Initialize immediately if Alpine is already loaded, otherwise wait
        if (typeof Alpine !== 'undefined' && Alpine.store) {
            initSidebarStore();
        } else {
            document.addEventListener('alpine:init', initSidebarStore);
        }
    </script>
@endpush
