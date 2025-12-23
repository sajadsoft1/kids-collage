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
    class="flex fixed inset-y-0 right-0 z-50 transition-all duration-300 ease-out"
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
        <div class="flex flex-col gap-2 items-center py-4 shrink-0">
            <div
                class="flex justify-center items-center w-11 h-11 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg shadow-blue-500/30">
                <i class="text-lg text-white fas fa-cube"></i>
            </div>
        </div>

        <!-- Modules Section - Scrollable -->
        <div
            class="flex overflow-y-auto overflow-x-hidden flex-col flex-1 gap-1 items-center px-2.5 py-2 w-full min-h-0">
            <span class="text-[10px] text-slate-500 uppercase tracking-wider mb-2 shrink-0">ماژول‌ها</span>
            @foreach ($modules as $module)
                <x-admin.sidebar.module-icon :module="$module" />
            @endforeach
        </div>

        <!-- Tools Section - Fixed at bottom -->
        <div class="flex flex-col gap-1 items-center px-2.5 pt-4 pb-4 w-full border-t shrink-0 border-slate-700/50">
            <span class="text-[10px] text-slate-500 uppercase tracking-wider mb-2">ابزارها</span>

            <!-- Search -->
            <div class="relative group">
                <button @click="$store.sidebar.openMenu('search')"
                    class="flex justify-center items-center w-11 h-11 rounded-xl transition-all duration-200 text-slate-400 hover:bg-slate-800 hover:text-white"
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
                    class="flex relative justify-center items-center w-11 h-11 rounded-xl transition-all duration-200 text-slate-400 hover:bg-slate-800 hover:text-white"
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
                    class="flex justify-center items-center w-11 h-11 rounded-xl transition-all duration-200 text-slate-400 hover:bg-slate-800 hover:text-white"
                    aria-label="انتخاب شعبه" aria-haspopup="true">
                    <i class="text-base fas fa-building"></i>
                </button>
                <div class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2"
                    x-text="$store.sidebar.currentBranch"></div>
            </div>

            <!-- Dark Mode Toggle -->
            <div class="relative group">
                <x-theme-toggle
                    class="flex justify-center items-center w-11 h-11 rounded-xl transition-all duration-200 text-slate-400 hover:bg-slate-800 hover:text-white" />
            </div>

            <!-- Settings -->
            <div class="relative group">
                <button @click="$store.sidebar.openMenu('settings')"
                    class="flex justify-center items-center w-11 h-11 rounded-xl transition-all duration-200 text-slate-400 hover:bg-slate-800 hover:text-white"
                    aria-label="تنظیمات" aria-haspopup="true">
                    <i class="text-base fas fa-cog"></i>
                </button>
                <div
                    class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
                    تنظیمات
                </div>
            </div>

            <!-- Profile -->
            <div class="relative mt-2 group">
                <button @click="$store.sidebar.openMenu('profile')"
                    class="flex justify-center items-center w-11 h-11 text-sm font-medium text-white bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg transition-all duration-200 shadow-blue-500/30 hover:shadow-blue-500/50"
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
    <div class="flex overflow-hidden flex-col shadow-xl transition-all duration-300 ease-out bg-slate-800 dark:bg-slate-900"
        x-show="$store.sidebar.menuVisible"
        :class="{
            'fixed inset-y-0 right-[72px] z-[60] w-[calc(100%-72px)]': $store.sidebar.isMobile,
            'w-[260px]': !$store.sidebar.isMobile,
            'absolute inset-y-0 right-[72px] shadow-2xl': !$store.sidebar.isMobile && (!$store.sidebar.isPinned ||
                $store.sidebar.isTablet)
        }">

        <!-- Module Title with Pin & Close Buttons -->
        <div class="flex justify-between items-center px-5 py-5 border-b border-slate-700/50">
            <h2 class="flex gap-3 items-center text-sm font-semibold text-slate-100">
                @foreach ($modules as $module)
                    @if (!Arr::get($module, 'is_direct_link', false))
                        <span x-show="$store.sidebar.activeModule === '{{ $module['key'] }}'"
                            class="flex gap-3 items-center">
                            <x-icon name="{{ $module['icon'] }}" class="text-blue-400" />
                            <span>{{ $module['title'] }}</span>
                        </span>
                    @endif
                @endforeach
                <span x-show="$store.sidebar.activeModule === 'search'" class="flex gap-3 items-center">
                    <i class="text-blue-400 fas fa-search"></i>
                    <span>جستجو</span>
                </span>
                <span x-show="$store.sidebar.activeModule === 'notifications'" class="flex gap-3 items-center">
                    <i class="text-blue-400 fas fa-bell"></i>
                    <span>اعلانات</span>
                </span>
                <span x-show="$store.sidebar.activeModule === 'branch'" class="flex gap-3 items-center">
                    <i class="text-blue-400 fas fa-building"></i>
                    <span>انتخاب شعبه</span>
                </span>
                <span x-show="$store.sidebar.activeModule === 'settings'" class="flex gap-3 items-center">
                    <i class="text-blue-400 fas fa-cog"></i>
                    <span>تنظیمات</span>
                </span>
                <span x-show="$store.sidebar.activeModule === 'profile'" class="flex gap-3 items-center">
                    <i class="text-blue-400 fas fa-user"></i>
                    <span>پروفایل</span>
                </span>
            </h2>

            <!-- Action Buttons -->
            <div class="flex gap-1 items-center">
                <!-- Pin Toggle: Large Desktop Only (lg and up) - Hidden on mobile and tablet -->
                <button @click="$store.sidebar.togglePin()"
                    class="hidden text-blue-400 lg:flex btn btn-ghost btn-xs btn-square hover:bg-slate-700/50"
                    :class="$store.sidebar.isPinned ? '' : 'rotate-45'" aria-label="پین کردن سایدبار">
                    <i class="text-sm fas fa-thumbtack"></i>
                </button>

                <!-- Desktop Close Button - Only visible on lg+ when NOT pinned -->
                <button @click="$store.sidebar.closeMenu(); $store.sidebar.menuVisible = false;"
                    x-show="!$store.sidebar.isPinned"
                    class="hidden lg:flex btn btn-ghost btn-xs btn-square hover:bg-slate-700/50 text-slate-400 hover:text-white"
                    aria-label="بستن منو">
                    <i class="text-sm fas fa-times"></i>
                </button>

                <!-- Mobile/Tablet Close Button - Large on mobile, small on tablet, hidden on desktop -->
                <button @click="$store.sidebar.closeMobile()"
                    class="w-14 h-14 text-3xl rounded-xl transition-all lg:hidden btn btn-ghost text-slate-300 hover:text-white hover:bg-slate-700/50 md:w-9 md:h-9 md:text-base md:btn-square"
                    aria-label="بستن منو">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Current Branch Indicator -->
        <div class="px-4 py-2 border-b bg-slate-700/30 border-slate-700/50">
            <div class="flex gap-2 items-center text-xs text-slate-400">
                <i class="fas fa-building"></i>
                <span x-text="$store.sidebar.currentBranch"></span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="overflow-y-auto flex-1 px-3 py-3 space-y-1" role="navigation">
            @foreach ($modules as $module)
                <x-admin.sidebar.module-menu :module="$module" />
            @endforeach

            <!-- Search Panel -->
            <div x-show="$store.sidebar.activeModule === 'search'" class="space-y-3">
                <div class="relative">
                    <i class="absolute right-3 top-1/2 text-sm -translate-y-1/2 fas fa-search text-slate-500"></i>
                    <input type="text" placeholder="جستجو در سیستم..."
                        class="py-2.5 pr-10 pl-4 w-full text-sm rounded-lg border-0 bg-slate-700 text-slate-200 placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <p class="text-xs text-slate-500">جستجو در تمام ماژول‌ها...</p>
            </div>

            <!-- Notifications Panel -->
            <div x-show="$store.sidebar.activeModule === 'notifications'" class="space-y-2">
                @forelse ($notifications as $notification)
                    <div class="p-3 rounded-lg transition-colors cursor-pointer bg-slate-700/50 hover:bg-slate-700">
                        <p class="text-sm text-slate-200">{{ $notification['title'] }}</p>
                        @if (!empty($notification['body']))
                            <p class="mt-1 text-xs text-slate-300">{{ $notification['body'] }}</p>
                        @endif
                        <p class="mt-1 text-xs text-slate-500">{{ $notification['created_at'] }}</p>
                    </div>
                @empty
                    <div class="p-3 rounded-lg bg-slate-700/50">
                        <p class="text-sm text-center text-slate-400">اعلانی وجود ندارد</p>
                    </div>
                @endforelse
                @if (count($notifications) > 0)
                    <a href="{{ route('admin.notification.index') }}" wire:navigate
                        class="block p-3 text-sm text-center text-blue-400 rounded-lg transition-colors bg-blue-600/20 hover:bg-blue-600/30 hover:text-blue-300">
                        مشاهده همه اعلانات
                    </a>
                @endif
            </div>

            <!-- Branch Panel -->
            <div x-show="$store.sidebar.activeModule === 'branch'" class="space-y-1">
                <a href="#"
                    @click.prevent="$store.sidebar.currentBranch = 'شعبه مرکزی'; try { localStorage.setItem('currentBranch', 'شعبه مرکزی'); } catch(e) {}"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200"
                    :class="$store.sidebar.currentBranch === 'شعبه مرکزی' ?
                        'bg-blue-600 text-white shadow-lg shadow-blue-600/20' :
                        'text-slate-400 hover:bg-slate-700/50 hover:text-white'">
                    <i class="w-5 text-sm text-center fas fa-check"
                        x-show="$store.sidebar.currentBranch === 'شعبه مرکزی'"></i>
                    <i class="w-5 text-sm text-center fas fa-building"
                        x-show="$store.sidebar.currentBranch !== 'شعبه مرکزی'"></i>
                    <span>شعبه مرکزی</span>
                </a>
                <a href="#"
                    @click.prevent="$store.sidebar.currentBranch = 'شعبه شمال'; try { localStorage.setItem('currentBranch', 'شعبه شمال'); } catch(e) {}"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200"
                    :class="$store.sidebar.currentBranch === 'شعبه شمال' ?
                        'bg-blue-600 text-white shadow-lg shadow-blue-600/20' :
                        'text-slate-400 hover:bg-slate-700/50 hover:text-white'">
                    <i class="w-5 text-sm text-center fas fa-check"
                        x-show="$store.sidebar.currentBranch === 'شعبه شمال'"></i>
                    <i class="w-5 text-sm text-center fas fa-building"
                        x-show="$store.sidebar.currentBranch !== 'شعبه شمال'"></i>
                    <span>شعبه شمال</span>
                </a>
                <a href="#"
                    @click.prevent="$store.sidebar.currentBranch = 'شعبه جنوب'; try { localStorage.setItem('currentBranch', 'شعبه جنوب'); } catch(e) {}"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200"
                    :class="$store.sidebar.currentBranch === 'شعبه جنوب' ?
                        'bg-blue-600 text-white shadow-lg shadow-blue-600/20' :
                        'text-slate-400 hover:bg-slate-700/50 hover:text-white'">
                    <i class="w-5 text-sm text-center fas fa-check"
                        x-show="$store.sidebar.currentBranch === 'شعبه جنوب'"></i>
                    <i class="w-5 text-sm text-center fas fa-building"
                        x-show="$store.sidebar.currentBranch !== 'شعبه جنوب'"></i>
                    <span>شعبه جنوب</span>
                </a>
            </div>

            <!-- Settings Panel -->
            <div x-show="$store.sidebar.activeModule === 'settings'" class="space-y-1">
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-slate-400 hover:bg-slate-700/50 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-palette"></i>
                    <span>ظاهر</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-slate-400 hover:bg-slate-700/50 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-globe"></i>
                    <span>زبان</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-slate-400 hover:bg-slate-700/50 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-shield-alt"></i>
                    <span>امنیت</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-slate-400 hover:bg-slate-700/50 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-database"></i>
                    <span>پشتیبان‌گیری</span>
                </a>
            </div>

            <!-- Profile Panel -->
            <div x-show="$store.sidebar.activeModule === 'profile'" class="space-y-3">
                <div class="flex gap-3 items-center p-3 rounded-lg bg-slate-700/50">
                    <div
                        class="flex justify-center items-center w-12 h-12 font-medium text-white bg-gradient-to-br from-blue-500 to-blue-600 rounded-full shadow-lg">
                        JD</div>
                    <div>
                        <p class="text-sm font-medium text-slate-200">جان دو</p>
                        <p class="text-xs text-slate-500">مدیر سیستم</p>
                    </div>
                </div>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-slate-400 hover:bg-slate-700/50 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-user"></i>
                    <span>ویرایش پروفایل</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-slate-400 hover:bg-slate-700/50 hover:text-white">
                    <i class="w-5 text-sm text-center fas fa-key"></i>
                    <span>تغییر رمز عبور</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm text-red-400 rounded-lg transition-all duration-200 hover:bg-red-600/20 hover:text-red-300">
                    <i class="w-5 text-sm text-center fas fa-sign-out-alt"></i>
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
                            // Sync state based on pin and active module
                            if (!this.isMobile && !this.isTablet) {
                                if (this.isPinned) {
                                    // If pinned: keep sidebar open for margin, menu visible only if active module exists
                                    this.sidebarOpen = true;
                                    this.menuVisible = this.activeModule !== '';
                                } else {
                                    // If unpinned, menu should follow sidebarOpen and active module
                                    this.menuVisible = this.sidebarOpen && this.activeModule !== '';
                                }
                            } else {
                                // Mobile/Tablet: menuVisible follows sidebarOpen
                                this.menuVisible = this.sidebarOpen;
                            }
                        } else {
                            this.sidebarOpen = false;
                            this.menuVisible = false;
                        }
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
                        this.isDirectLinkActive = true;
                        // Close sidebar if unpinned, keep open if pinned (but menu closed)
                        if (!this.isMobile && !this.isTablet) {
                            if (!this.isPinned) {
                                this.sidebarOpen = false;
                            } else {
                                // When pinned, sidebar stays open but menu closes
                                this.sidebarOpen = true;
                            }
                        } else {
                            this.sidebarOpen = false;
                        }
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
                        // Sync sidebarOpen and menuVisible based on pin state
                        this.syncStateAfterPinChange();
                    },
                    syncStateAfterPinChange() {
                        // Helper function to sync state after pin change
                        if (!this.isMobile && !this.isTablet) {
                            // Desktop only
                            if (this.isPinned) {
                                // When pinned: sidebar should be open (for margin), menu visible only if active module exists
                                this.sidebarOpen = true;
                                // Keep menu visible only if we have active module
                                if (this.activeModule === '') {
                                    this.menuVisible = false;
                                }
                            } else {
                                // When unpinned: sidebar becomes floating overlay
                                this.sidebarOpen = false;
                                // Menu visible only if we have active module
                                if (this.activeModule === '') {
                                    this.menuVisible = false;
                                }
                            }
                        }
                    },
                    syncStateAfterNavigation() {
                        // Helper function to sync state after navigation
                        if (!this.isMobile && !this.isTablet) {
                            // Desktop only
                            if (this.isDirectLinkActive) {
                                // Direct link is active: close menu, but keep sidebar open if pinned
                                this.menuVisible = false;
                                if (this.isPinned) {
                                    this.sidebarOpen = true;
                                } else {
                                    this.sidebarOpen = false;
                                }
                            } else if (this.isPinned) {
                                // If pinned: keep menu visible if we have active module
                                if (this.activeModule !== '') {
                                    this.menuVisible = true;
                                    this.sidebarOpen = true;
                                } else {
                                    // No active module, close menu but keep sidebar open
                                    this.menuVisible = false;
                                    this.sidebarOpen = true;
                                }
                            } else {
                                // If unpinned: menu should be floating overlay
                                if (this.activeModule !== '') {
                                    this.menuVisible = true;
                                    this.sidebarOpen = false;
                                } else {
                                    this.menuVisible = false;
                                    this.sidebarOpen = false;
                                }
                            }
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

                // Sync state after navigation based on pin state and active module
                // Reset activeModule to default (which is empty string for direct links)
                store.activeModule = '{{ $defaultModule }}';

                // Sync state based on pin status and isDirectLinkActive
                if (typeof store.syncStateAfterNavigation === 'function') {
                    store.syncStateAfterNavigation();
                } else {
                    // Fallback logic if function doesn't exist yet
                    if (!store.isMobile && !store.isTablet) {
                        // Desktop only
                        if (store.isDirectLinkActive) {
                            // Direct link is active: close menu, but keep sidebar open if pinned
                            store.menuVisible = false;
                            if (store.isPinned) {
                                store.sidebarOpen = true;
                            } else {
                                store.sidebarOpen = false;
                            }
                        } else if (store.isPinned) {
                            // If pinned: keep menu visible if we have active module
                            if (store.activeModule !== '') {
                                store.menuVisible = true;
                                store.sidebarOpen = true;
                            } else {
                                // No active module, close menu but keep sidebar open
                                store.menuVisible = false;
                                store.sidebarOpen = true;
                            }
                        } else {
                            // If unpinned: menu should be floating overlay
                            if (store.activeModule !== '') {
                                store.menuVisible = true;
                                store.sidebarOpen = false;
                            } else {
                                store.menuVisible = false;
                                store.sidebarOpen = false;
                            }
                        }
                    }
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
                                // Sync state after pin change
                                if (typeof store.syncStateAfterPinChange === 'function') {
                                    store.syncStateAfterPinChange();
                                }
                            }
                        } else {
                            // Default to true if not set
                            if (!store.isPinned) {
                                store.isPinned = true;
                                // Sync state after pin change
                                if (typeof store.syncStateAfterPinChange === 'function') {
                                    store.syncStateAfterPinChange();
                                }
                            }
                        }
                    } catch (e) {
                        // localStorage not available
                        if (typeof Alpine !== 'undefined' && Alpine.store('sidebar')) {
                            const store = Alpine.store('sidebar');
                            if (!store.isPinned) {
                                store.isPinned = true;
                                // Sync state after pin change
                                if (typeof store.syncStateAfterPinChange === 'function') {
                                    store.syncStateAfterPinChange();
                                }
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
