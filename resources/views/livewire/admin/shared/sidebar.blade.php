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

<aside x-data="{}" x-init="$store.sidebar.init()"
    class="fixed z-50 flex transition-all duration-300 ease-out right-0 inset-y-0"
    :class="{
        'w-[72px]': !$store.sidebar.menuVisible && !$store.sidebar.isMobile,
        'w-auto': $store.sidebar.menuVisible && !$store.sidebar.isMobile,
        'hidden': !$store.sidebar.menuVisible && $store.sidebar.isMobile,
        'w-auto': $store.sidebar.menuVisible && $store.sidebar.isMobile
    }">

    <!-- Icon Column (Level 1) - Hidden on mobile by default, shows when menu is opened -->
    <div id="sidebar"
        class="w-[72px] bg-slate-900 dark:bg-slate-950 flex flex-col items-center h-full shrink-0 shadow-xl overflow-x-hidden"
        :class="{
            'hidden': !$store.sidebar.menuVisible && $store.sidebar.isMobile
        }">

        <!-- Logo - Fixed at top -->
        <div class="shrink-0 py-4 flex flex-col items-center gap-2">
            <div
                class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                <i class="fas fa-cube text-white text-lg"></i>
            </div>
        </div>

        <!-- Modules Section - Scrollable -->
        <div
            class="flex-1 overflow-y-auto overflow-x-hidden flex flex-col items-center gap-1 py-2 w-full px-2.5 min-h-0">
            <span class="text-[10px] text-slate-500 uppercase tracking-wider mb-2 shrink-0">ماژول‌ها</span>
            @foreach ($modules as $module)
                <x-admin.sidebar.module-icon :module="$module" />
            @endforeach
        </div>

        <!-- Tools Section - Fixed at bottom -->
        <div class="shrink-0 flex flex-col items-center gap-1 pt-4 pb-4 border-t border-slate-700/50 w-full px-2.5">
            <span class="text-[10px] text-slate-500 uppercase tracking-wider mb-2">ابزارها</span>

            <!-- Search -->
            <div class="relative group">
                <button @click="$store.sidebar.openMenu('search')"
                    class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200"
                    aria-label="جستجو" aria-haspopup="true">
                    <i class="text-base fas fa-search"></i>
                </button>
                <div
                    class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
                    جستجو
                </div>
            </div>

            <!-- Notifications -->
            <div class="relative group">
                <button @click="$store.sidebar.openMenu('notifications')"
                    class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200 relative"
                    aria-label="اعلانات" aria-haspopup="true">
                    <i class="text-base fas fa-bell"></i>
                    <span x-show="$store.sidebar.notificationCount > 0"
                        class="absolute top-1.5 right-1.5 min-w-[18px] h-[18px] bg-red-500 rounded-full text-[10px] text-white flex items-center justify-center font-medium px-1"
                        x-text="$store.sidebar.notificationCount > 9 ? '9+' : $store.sidebar.notificationCount"></span>
                </button>
                <div
                    class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
                    اعلانات
                </div>
            </div>

            <!-- Branch -->
            <div class="relative group">
                <button @click="$store.sidebar.openMenu('branch')"
                    class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200"
                    aria-label="انتخاب شعبه" aria-haspopup="true">
                    <i class="text-base fas fa-building"></i>
                </button>
                <div class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2"
                    x-text="$store.sidebar.currentBranch"></div>
            </div>

            <!-- Dark Mode Toggle -->
            <div class="relative group">
                <x-theme-toggle
                    class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200" />
            </div>

            <!-- Settings -->
            <div class="relative group">
                <button @click="$store.sidebar.openMenu('settings')"
                    class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200"
                    aria-label="تنظیمات" aria-haspopup="true">
                    <i class="text-base fas fa-cog"></i>
                </button>
                <div
                    class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
                    تنظیمات
                </div>
            </div>

            <!-- Profile -->
            <div class="relative group mt-2">
                <button @click="$store.sidebar.openMenu('profile')"
                    class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-sm font-medium shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-200"
                    aria-label="پروفایل" aria-haspopup="true">
                    JD
                </button>
                <div
                    class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
                    جان دو
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Column (Level 2) - Shows next to level 1 on mobile, fixed width on tablet+, floating on tablet/unpinned desktop -->
    <div class="bg-slate-800 dark:bg-slate-900 flex flex-col transition-all duration-300 ease-out overflow-hidden shadow-xl"
        x-show="$store.sidebar.menuVisible"
        :class="{
            'fixed inset-y-0 right-[72px] z-[60] w-[calc(100%-72px)]': $store.sidebar.isMobile,
            'w-[260px]': !$store.sidebar.isMobile,
            'absolute inset-y-0 right-[72px] shadow-2xl': !$store.sidebar.isMobile && (!$store.sidebar.isPinned ||
                $store.sidebar.isTablet)
        }">

        <!-- Module Title with Pin & Close Buttons -->
        <div class="px-5 py-5 border-b border-slate-700/50 flex items-center justify-between">
            <h2 class="font-semibold text-slate-100 text-sm flex items-center gap-3">
                @foreach ($modules as $module)
                    @if (!Arr::get($module, 'is_direct_link', false))
                        <span x-show="$store.sidebar.activeModule === '{{ $module['key'] }}'"
                            class="flex items-center gap-3">
                            <x-icon name="{{ $module['icon'] }}" class="text-blue-400" />
                            <span>{{ $module['title'] }}</span>
                        </span>
                    @endif
                @endforeach
                <span x-show="$store.sidebar.activeModule === 'search'" class="flex items-center gap-3">
                    <i class="fas fa-search text-blue-400"></i>
                    <span>جستجو</span>
                </span>
                <span x-show="$store.sidebar.activeModule === 'notifications'" class="flex items-center gap-3">
                    <i class="fas fa-bell text-blue-400"></i>
                    <span>اعلانات</span>
                </span>
                <span x-show="$store.sidebar.activeModule === 'branch'" class="flex items-center gap-3">
                    <i class="fas fa-building text-blue-400"></i>
                    <span>انتخاب شعبه</span>
                </span>
                <span x-show="$store.sidebar.activeModule === 'settings'" class="flex items-center gap-3">
                    <i class="fas fa-cog text-blue-400"></i>
                    <span>تنظیمات</span>
                </span>
                <span x-show="$store.sidebar.activeModule === 'profile'" class="flex items-center gap-3">
                    <i class="fas fa-user text-blue-400"></i>
                    <span>پروفایل</span>
                </span>
            </h2>

            <!-- Action Buttons -->
            <div class="flex items-center gap-1">
                <!-- Pin Toggle: Large Desktop Only (lg and up) - Hidden on mobile and tablet -->
                <button @click="$store.sidebar.togglePin()"
                    class="hidden lg:flex btn btn-ghost btn-xs btn-square hover:bg-slate-700/50 text-blue-400"
                    :class="$store.sidebar.isPinned ? '' : 'rotate-45'" aria-label="پین کردن سایدبار">
                    <i class="fas fa-thumbtack text-sm"></i>
                </button>

                <!-- Desktop Close Button - Only visible on lg+ when NOT pinned -->
                <button @click="$store.sidebar.closeMenu(); $store.sidebar.menuVisible = false;"
                    x-show="!$store.sidebar.isPinned"
                    class="hidden lg:flex btn btn-ghost btn-xs btn-square hover:bg-slate-700/50 text-slate-400 hover:text-white"
                    aria-label="بستن منو">
                    <i class="fas fa-times text-sm"></i>
                </button>

                <!-- Mobile/Tablet Close Button - Large on mobile, small on tablet, hidden on desktop -->
                <button @click="$store.sidebar.closeMobile()"
                    class="lg:hidden btn btn-ghost text-slate-300 hover:text-white hover:bg-slate-700/50 transition-all w-14 h-14 text-3xl rounded-xl md:w-9 md:h-9 md:text-base md:btn-square"
                    aria-label="بستن منو">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Current Branch Indicator -->
        <div class="px-4 py-2 bg-slate-700/30 border-b border-slate-700/50">
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <i class="fas fa-building"></i>
                <span x-text="$store.sidebar.currentBranch"></span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-1" role="navigation">
            @foreach ($modules as $module)
                <x-admin.sidebar.module-menu :module="$module" />
            @endforeach

            <!-- Search Panel -->
            <div x-show="$store.sidebar.activeModule === 'search'" class="space-y-3">
                <div class="relative">
                    <i class="fas fa-search absolute top-1/2 -translate-y-1/2 text-slate-500 text-sm right-3"></i>
                    <input type="text" placeholder="جستجو در سیستم..."
                        class="w-full py-2.5 pr-10 pl-4 bg-slate-700 border-0 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <p class="text-slate-500 text-xs">جستجو در تمام ماژول‌ها...</p>
            </div>

            <!-- Notifications Panel -->
            <div x-show="$store.sidebar.activeModule === 'notifications'" class="space-y-2">
                @forelse ($notifications as $notification)
                    <div class="p-3 bg-slate-700/50 rounded-lg hover:bg-slate-700 transition-colors cursor-pointer">
                        <p class="text-slate-200 text-sm">{{ $notification['title'] }}</p>
                        @if (!empty($notification['body']))
                            <p class="text-slate-300 text-xs mt-1">{{ $notification['body'] }}</p>
                        @endif
                        <p class="text-slate-500 text-xs mt-1">{{ $notification['created_at'] }}</p>
                    </div>
                @empty
                    <div class="p-3 bg-slate-700/50 rounded-lg">
                        <p class="text-slate-400 text-sm text-center">اعلانی وجود ندارد</p>
                    </div>
                @endforelse
                @if (count($notifications) > 0)
                    <a href="{{ route('admin.notification.index') }}" wire:navigate
                        class="block p-3 bg-blue-600/20 hover:bg-blue-600/30 rounded-lg text-center text-sm text-blue-400 hover:text-blue-300 transition-colors">
                        مشاهده همه اعلانات
                    </a>
                @endif
            </div>

            <!-- Branch Panel -->
            <div x-show="$store.sidebar.activeModule === 'branch'" class="space-y-1">
                <a href="#"
                    @click.prevent="$store.sidebar.currentBranch = 'شعبه مرکزی'; try { localStorage.setItem('currentBranch', 'شعبه مرکزی'); } catch(e) {}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200"
                    :class="$store.sidebar.currentBranch === 'شعبه مرکزی' ?
                        'bg-blue-600 text-white shadow-lg shadow-blue-600/20' :
                        'text-slate-400 hover:bg-slate-700/50 hover:text-white'">
                    <i class="fas fa-check w-5 text-center text-sm"
                        x-show="$store.sidebar.currentBranch === 'شعبه مرکزی'"></i>
                    <i class="fas fa-building w-5 text-center text-sm"
                        x-show="$store.sidebar.currentBranch !== 'شعبه مرکزی'"></i>
                    <span>شعبه مرکزی</span>
                </a>
                <a href="#"
                    @click.prevent="$store.sidebar.currentBranch = 'شعبه شمال'; try { localStorage.setItem('currentBranch', 'شعبه شمال'); } catch(e) {}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200"
                    :class="$store.sidebar.currentBranch === 'شعبه شمال' ?
                        'bg-blue-600 text-white shadow-lg shadow-blue-600/20' :
                        'text-slate-400 hover:bg-slate-700/50 hover:text-white'">
                    <i class="fas fa-check w-5 text-center text-sm"
                        x-show="$store.sidebar.currentBranch === 'شعبه شمال'"></i>
                    <i class="fas fa-building w-5 text-center text-sm"
                        x-show="$store.sidebar.currentBranch !== 'شعبه شمال'"></i>
                    <span>شعبه شمال</span>
                </a>
                <a href="#"
                    @click.prevent="$store.sidebar.currentBranch = 'شعبه جنوب'; try { localStorage.setItem('currentBranch', 'شعبه جنوب'); } catch(e) {}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200"
                    :class="$store.sidebar.currentBranch === 'شعبه جنوب' ?
                        'bg-blue-600 text-white shadow-lg shadow-blue-600/20' :
                        'text-slate-400 hover:bg-slate-700/50 hover:text-white'">
                    <i class="fas fa-check w-5 text-center text-sm"
                        x-show="$store.sidebar.currentBranch === 'شعبه جنوب'"></i>
                    <i class="fas fa-building w-5 text-center text-sm"
                        x-show="$store.sidebar.currentBranch !== 'شعبه جنوب'"></i>
                    <span>شعبه جنوب</span>
                </a>
            </div>

            <!-- Settings Panel -->
            <div x-show="$store.sidebar.activeModule === 'settings'" class="space-y-1">
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-700/50 hover:text-white text-sm transition-all duration-200">
                    <i class="fas fa-palette w-5 text-center text-sm"></i>
                    <span>ظاهر</span>
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-700/50 hover:text-white text-sm transition-all duration-200">
                    <i class="fas fa-globe w-5 text-center text-sm"></i>
                    <span>زبان</span>
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-700/50 hover:text-white text-sm transition-all duration-200">
                    <i class="fas fa-shield-alt w-5 text-center text-sm"></i>
                    <span>امنیت</span>
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-700/50 hover:text-white text-sm transition-all duration-200">
                    <i class="fas fa-database w-5 text-center text-sm"></i>
                    <span>پشتیبان‌گیری</span>
                </a>
            </div>

            <!-- Profile Panel -->
            <div x-show="$store.sidebar.activeModule === 'profile'" class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-slate-700/50 rounded-lg">
                    <div
                        class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium shadow-lg">
                        JD</div>
                    <div>
                        <p class="text-slate-200 text-sm font-medium">جان دو</p>
                        <p class="text-slate-500 text-xs">مدیر سیستم</p>
                    </div>
                </div>
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-700/50 hover:text-white text-sm transition-all duration-200">
                    <i class="fas fa-user w-5 text-center text-sm"></i>
                    <span>ویرایش پروفایل</span>
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:bg-slate-700/50 hover:text-white text-sm transition-all duration-200">
                    <i class="fas fa-key w-5 text-center text-sm"></i>
                    <span>تغییر رمز عبور</span>
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-400 hover:bg-red-600/20 hover:text-red-300 text-sm transition-all duration-200">
                    <i class="fas fa-sign-out-alt w-5 text-center text-sm"></i>
                    <span>خروج</span>
                </a>
            </div>
        </nav>
    </div>
</aside>

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

                // Load currentBranch from localStorage
                let savedCurrentBranch = '{{ $currentBranch }}';
                try {
                    const savedBranch = localStorage.getItem('currentBranch');
                    if (savedBranch !== null) {
                        savedCurrentBranch = savedBranch;
                    } else {
                        // Save initial value to localStorage
                        localStorage.setItem('currentBranch', savedCurrentBranch);
                    }
                } catch (e) {
                    // localStorage not available
                }

                // Load isPinned from localStorage (default: true)
                let savedIsPinned = true;
                try {
                    const savedPin = localStorage.getItem('sidebarPinned');
                    if (savedPin !== null) {
                        savedIsPinned = savedPin === 'true';
                    }
                } catch (e) {
                    // localStorage not available
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
                    get isPinned() {
                        return this._isPinned;
                    },
                    set isPinned(value) {
                        this._isPinned = value;
                        try {
                            localStorage.setItem('sidebarPinned', value.toString());
                        } catch (e) {
                            // localStorage not available
                        }
                    },
                    menuVisible: false,
                    activeModule: '{{ $defaultModule }}',
                    activeMenu: '',
                    isDirectLinkActive: {{ $isDirectLinkActive ? 'true' : 'false' }},
                    notificationCount: {{ $notificationCount }},
                    currentBranch: savedCurrentBranch,
                    isMobile: false,
                    isTablet: false,
                    init() {
                        // Initialize _sidebarOpen and _isPinned
                        this._sidebarOpen = savedSidebarOpen;
                        this._isPinned = savedIsPinned;
                        this.updateDeviceType();

                        // Listen for resize
                        window.addEventListener('resize', () => {
                            this.updateDeviceType();
                        });

                        if (window.innerWidth >= 768 && !this.isDirectLinkActive) {
                            // Use saved value
                        } else {
                            this.sidebarOpen = false;
                        }
                        // menuVisible follows sidebarOpen state initially
                        this.menuVisible = this.sidebarOpen;
                    },
                    toggleSidebar() {
                        // On mobile, toggle both level 1 and level 2 together
                        if (this.isMobile) {
                            this.menuVisible = !this.menuVisible;
                            this.sidebarOpen = this.menuVisible;
                            // Set default module if none is active when opening
                            if (this.menuVisible && this.activeModule === '') {
                                this.activeModule = '{{ $defaultModule }}';
                            }
                            return;
                        }
                        // On desktop, don't toggle when direct link is active
                        if (this.isDirectLinkActive) {
                            return;
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
                        this.menuVisible = true;
                        // On mobile, also set sidebarOpen to show level 1
                        if (this.isMobile) {
                            this.sidebarOpen = true;
                        }
                        // On desktop when pinned, also set sidebarOpen so content margin adjusts
                        if (!this.isMobile && !this.isTablet && this.isPinned) {
                            this.sidebarOpen = true;
                        }
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
                        // Close level 2 menu when clicking outside
                        // Don't close if pinned on desktop
                        if (!this.isMobile && !this.isTablet && this.isPinned) {
                            return; // Keep open when pinned on desktop
                        }
                        if (!this.sidebarOpen) {
                            this.menuVisible = false;
                            this.activeModule = '';
                        }
                    },
                    togglePin() {
                        this.isPinned = !this.isPinned;
                        // When pinned, sidebar is fixed and sidebarOpen should be true
                        // When unpinned, sidebar becomes floating overlay
                        if (this.isPinned && this.menuVisible) {
                            this.sidebarOpen = true;
                        } else if (!this.isPinned) {
                            this.sidebarOpen = false;
                        }
                    },
                    closeMobile() {
                        // Close sidebar completely on mobile/tablet (both level 1 and 2)
                        this.menuVisible = false;
                        this.sidebarOpen = false;
                        this.activeModule = '';
                    },
                    updateDeviceType() {
                        const width = window.innerWidth;
                        this.isMobile = width < 768; // < md
                        this.isTablet = width >= 768 && width < 1024; // md to lg
                    },
                    setActiveMenu(menu) {
                        this.activeMenu = menu;
                    },
                    getBreadcrumb() {
                        const modules = {
                            @foreach ($modules as $module)
                                @if (!Arr::get($module, 'is_direct_link', false))
                                    '{{ $module['key'] }}': '{{ $module['title'] }}',
                                @endif
                            @endforeach
                        };
                        return modules[this.activeModule] || 'داشبورد';
                    }
                });
            } else {
                // Update store values if they changed (e.g., after navigation)
                const store = Alpine.store('sidebar');
                const previousActiveModule = store.activeModule;
                store.activeModule = '{{ $defaultModule }}';
                store.isDirectLinkActive = {{ $isDirectLinkActive ? 'true' : 'false' }};
                store.notificationCount = {{ $notificationCount }};

                // Reload isPinned from localStorage to ensure it persists across wire:navigate
                // Use setter to ensure reactivity works correctly
                try {
                    const savedPin = localStorage.getItem('sidebarPinned');
                    if (savedPin !== null) {
                        const pinValue = savedPin === 'true';
                        // Set both _isPinned and use setter for reactivity
                        store._isPinned = pinValue;
                        // Trigger setter to ensure Alpine reactivity
                        if (store.isPinned !== pinValue) {
                            store.isPinned = pinValue;
                        }
                    } else {
                        // Default to true if not set
                        store._isPinned = true;
                        if (!store.isPinned) {
                            store.isPinned = true;
                        }
                    }
                } catch (e) {
                    // localStorage not available
                    store._isPinned = true;
                    if (!store.isPinned) {
                        store.isPinned = true;
                    }
                }

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

        // Reload isPinned state after wire:navigate navigation
        // Use a unique function name to avoid duplicate listeners
        if (!window.__sidebarPinnedReloader) {
            window.__sidebarPinnedReloader = () => {
                if (typeof Alpine !== 'undefined' && Alpine.store('sidebar')) {
                    try {
                        const savedPin = localStorage.getItem('sidebarPinned');
                        const store = Alpine.store('sidebar');
                        if (savedPin !== null) {
                            const pinValue = savedPin === 'true';
                            // Use setter to ensure reactivity and proper state sync
                            if (store.isPinned !== pinValue) {
                                store.isPinned = pinValue;
                            }
                        } else {
                            // Default to true if not set
                            if (!store.isPinned) {
                                store.isPinned = true;
                            }
                        }
                    } catch (e) {
                        // localStorage not available
                        if (typeof Alpine !== 'undefined' && Alpine.store('sidebar')) {
                            if (!Alpine.store('sidebar').isPinned) {
                                Alpine.store('sidebar').isPinned = true;
                            }
                        }
                    }
                }
            };

            // Add event listeners only once
            document.addEventListener('alpine:navigated', () => {
                // Wait a bit for Alpine to fully initialize
                setTimeout(window.__sidebarPinnedReloader, 50);
            }, {
                once: false
            });

            // Also reload when Livewire finishes updating
            if (typeof Livewire !== 'undefined') {
                Livewire.hook('morph.updated', () => {
                    // Reload after navigation completes
                    setTimeout(window.__sidebarPinnedReloader, 100);
                });
            }
        }

        // Immediately reload on script execution (after a short delay to ensure Alpine is ready)
        setTimeout(window.__sidebarPinnedReloader, 10);
    </script>
@endpush
