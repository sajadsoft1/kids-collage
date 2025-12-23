@php
    use Illuminate\Support\Arr;
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
        class="w-[72px] bg-base-200 dark:bg-base-100 flex flex-col items-center h-full shrink-0 shadow-xl overflow-x-hidden"
        :class="{
            'hidden': !$store.sidebar.menuVisible && $store.sidebar.isMobile
        }">

        <!-- Logo - Fixed at top -->
        <div
            class="flex flex-col justify-center items-center h-14 border-b shrink-0 border-base-300 dark:border-base-content/10">
            <div class="flex justify-center items-center w-10 h-10 rounded-xl shadow-lg bg-primary shadow-primary/30">
                <i class="text-base text-primary-content fas fa-cube"></i>
            </div>
        </div>

        <!-- Modules Section - Scrollable -->
        <div class="flex overflow-y-auto overflow-x-hidden flex-col flex-1 gap-1 items-center px-2.5 py-2 w-full min-h-0"
            wire:scroll>
            <span class="text-[10px] text-base-content/50 uppercase tracking-wider mb-2 shrink-0">ماژول‌ها</span>
            @foreach ($modules as $module)
                <x-admin.sidebar.module-icon :module="$module" />
            @endforeach
        </div>

        <!-- Tools Section - Fixed at bottom -->
        <div
            class="flex flex-col gap-1 items-center px-2.5 pt-4 pb-4 w-full border-t shrink-0 border-base-300 dark:border-base-content/10">
            <span class="text-[10px] text-base-content/50 uppercase tracking-wider mb-2">ابزارها</span>

            <!-- Search -->
            <div class="relative group">
                <button @click="$store.sidebar.openMenu('search')"
                    class="flex justify-center items-center w-11 h-11 rounded-xl transition-all duration-200 text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content"
                    aria-label="جستجو" aria-haspopup="true">
                    <i class="text-base fas fa-search"></i>
                </button>
                <div
                    class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-base-200 dark:bg-base-300 text-base-content text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
                    جستجو
                </div>
            </div>

            <!-- Notifications -->
            <div class="relative group">
                <button @click="$store.sidebar.openMenu('notifications')"
                    class="flex relative justify-center items-center w-11 h-11 rounded-xl transition-all duration-200 text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content"
                    aria-label="اعلانات" aria-haspopup="true">
                    <i class="text-base fas fa-bell"></i>
                    <span x-show="$store.sidebar.notificationCount > 0"
                        class="absolute top-1.5 right-1.5 min-w-[18px] h-[18px] bg-red-500 rounded-full text-[10px] text-white flex items-center justify-center font-medium px-1"
                        x-text="$store.sidebar.notificationCount > 9 ? '9+' : $store.sidebar.notificationCount"></span>
                </button>
                <div
                    class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-base-200 dark:bg-base-300 text-base-content text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
                    اعلانات
                </div>
            </div>

            <!-- Dark Mode Toggle -->
            <div class="relative group">
                <x-theme-toggle
                    class="w-11 h-11 rounded-xltext-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content" />
            </div>

            <!-- Settings -->
            <div class="relative group">
                <button @click="$store.sidebar.openMenu('settings')"
                    class="flex justify-center items-center w-11 h-11 rounded-xl transition-all duration-200 text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content"
                    aria-label="تنظیمات" aria-haspopup="true">
                    <i class="text-base fas fa-cog"></i>
                </button>
                <div
                    class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-base-200 dark:bg-base-300 text-base-content text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
                    تنظیمات
                </div>
            </div>

            <!-- Profile -->
            <div class="relative mt-2 group">
                <button @click="$store.sidebar.openMenu('profile')"
                    class="flex justify-center items-center w-11 h-11 text-sm font-medium rounded-xl shadow-lg transition-all duration-200 text-primary-content bg-primary shadow-primary/30 hover:shadow-primary/50"
                    aria-label="پروفایل" aria-haspopup="true">
                    JD
                </button>
                <div
                    class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-base-200 dark:bg-base-300 text-base-content text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
                    جان دو
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Column (Level 2) - Shows next to level 1 on mobile, fixed width on tablet+, floating on tablet/unpinned desktop -->
    <div class="flex overflow-hidden flex-col shadow-xl transition-all duration-300 ease-out bg-base-200 dark:bg-base-100"
        x-show="$store.sidebar.menuVisible"
        :class="{
            'fixed inset-y-0 right-[72px] z-[60] w-[calc(100%-72px)]': $store.sidebar.isMobile,
            'w-[260px]': !$store.sidebar.isMobile,
            'absolute inset-y-0 right-[72px] shadow-2xl': !$store.sidebar.isMobile && (!$store.sidebar.isPinned ||
                $store.sidebar.isTablet)
        }">

        <!-- Module Title with Pin & Close Buttons -->
        <div class="flex justify-between items-center px-5 h-14 border-b border-base-300 dark:border-base-content/10">
            <h2 class="flex gap-3 items-center text-sm font-semibold text-base-content">
                @foreach ($modules as $module)
                    @if (!Arr::get($module, 'is_direct_link', false))
                        <span x-show="$store.sidebar.activeModule === '{{ $module['key'] }}'"
                            class="flex gap-3 items-center">
                            <x-icon name="{{ $module['icon'] }}" class="text-primary" />
                            <span>{{ $module['title'] }}</span>
                        </span>
                    @endif
                @endforeach
                <span x-show="$store.sidebar.activeModule === 'search'" class="flex gap-3 items-center">
                    <i class="text-primary fas fa-search"></i>
                    <span>جستجو</span>
                </span>
                <span x-show="$store.sidebar.activeModule === 'notifications'" class="flex gap-3 items-center">
                    <i class="text-primary fas fa-bell"></i>
                    <span>اعلانات</span>
                </span>
                <span x-show="$store.sidebar.activeModule === 'settings'" class="flex gap-3 items-center">
                    <i class="text-primary fas fa-cog"></i>
                    <span>تنظیمات</span>
                </span>
                <span x-show="$store.sidebar.activeModule === 'profile'" class="flex gap-3 items-center">
                    <i class="text-primary fas fa-user"></i>
                    <span>پروفایل</span>
                </span>
            </h2>

            <!-- Action Buttons -->
            <div class="flex gap-1 items-center">
                <!-- Pin Toggle: Large Desktop Only (lg and up) - Hidden on mobile and tablet -->
                <button @click="$store.sidebar.togglePin()"
                    class="hidden text-primary lg:flex btn btn-ghost btn-xs btn-square hover:bg-base-200 dark:hover:bg-base-300"
                    :class="$store.sidebar.isPinned ? '' : 'rotate-45'" aria-label="پین کردن سایدبار">
                    <i class="text-sm fas fa-thumbtack"></i>
                </button>

                <!-- Desktop Close Button - Only visible on lg+ when NOT pinned -->
                <button @click="$store.sidebar.closeMenu(); $store.sidebar.menuVisible = false;"
                    x-show="!$store.sidebar.isPinned"
                    class="hidden lg:flex btn btn-ghost btn-xs btn-square hover:bg-base-200 dark:hover:bg-base-300 text-base-content/60 hover:text-base-content"
                    aria-label="بستن منو">
                    <i class="text-sm fas fa-times"></i>
                </button>

                <!-- Mobile/Tablet Close Button - Large on mobile, small on tablet, hidden on desktop -->
                <button @click="$store.sidebar.closeMobile()"
                    class="w-14 h-14 text-3xl rounded-xl transition-all lg:hidden btn btn-ghost text-base-content/70 hover:text-base-content hover:bg-base-200 dark:hover:bg-base-300 md:w-9 md:h-9 md:text-base md:btn-square"
                    aria-label="بستن منو">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="overflow-y-auto flex-1 px-3 py-3 space-y-1" role="navigation" wire:scroll>
            @foreach ($modules as $module)
                <x-admin.sidebar.module-menu :module="$module" />
            @endforeach

            <!-- Search Panel -->
            <div x-show="$store.sidebar.activeModule === 'search'" class="space-y-3">
                <div class="relative">
                    <i class="absolute right-3 top-1/2 text-sm -translate-y-1/2 fas fa-search text-base-content/50"></i>
                    <input type="text" placeholder="جستجو در سیستم..."
                        class="py-2.5 pr-10 pl-4 w-full text-sm rounded-lg border-0 bg-base-200 dark:bg-base-300 text-base-content placeholder-base-content/50 focus:ring-2 focus:ring-primary focus:outline-none">
                </div>
                <p class="text-xs text-base-content/50">جستجو در تمام ماژول‌ها...</p>
            </div>

            <!-- Notifications Panel -->
            <div x-show="$store.sidebar.activeModule === 'notifications'" class="space-y-2">
                @forelse ($notifications as $notification)
                    <div
                        class="p-3 rounded-lg transition-colors cursor-pointer bg-base-200 dark:bg-base-300 hover:bg-base-200/80 dark:hover:bg-base-300/80">
                        <p class="text-sm text-base-content">{{ $notification['title'] }}</p>
                        @if (!empty($notification['body']))
                            <p class="mt-1 text-xs text-base-content/70">{{ $notification['body'] }}</p>
                        @endif
                        <p class="mt-1 text-xs text-base-content/50">{{ $notification['created_at'] }}</p>
                    </div>
                @empty
                    <div class="p-3 rounded-lg bg-base-200 dark:bg-base-300">
                        <p class="text-sm text-center text-base-content/60">اعلانی وجود ندارد</p>
                    </div>
                @endforelse
                @if (count($notifications) > 0)
                    <a href="{{ route('admin.notification.index') }}" wire:navigate
                        class="block p-3 text-sm text-center rounded-lg transition-colors text-primary bg-primary/20 hover:bg-primary/30 hover:text-primary">
                        مشاهده همه اعلانات
                    </a>
                @endif
            </div>

            <!-- Settings Panel -->
            <div x-show="$store.sidebar.activeModule === 'settings'" class="space-y-1">
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content">
                    <i class="w-5 text-sm text-center fas fa-palette"></i>
                    <span>ظاهر</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content">
                    <i class="w-5 text-sm text-center fas fa-globe"></i>
                    <span>زبان</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content">
                    <i class="w-5 text-sm text-center fas fa-shield-alt"></i>
                    <span>امنیت</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content">
                    <i class="w-5 text-sm text-center fas fa-database"></i>
                    <span>پشتیبان‌گیری</span>
                </a>
            </div>

            <!-- Profile Panel -->
            <div x-show="$store.sidebar.activeModule === 'profile'" class="space-y-3">
                <div class="flex gap-3 items-center p-3 rounded-lg bg-base-200 dark:bg-base-300">
                    <div
                        class="flex justify-center items-center w-12 h-12 font-medium rounded-full shadow-lg text-primary-content bg-primary">
                        JD</div>
                    <div>
                        <p class="text-sm font-medium text-base-content">جان دو</p>
                        <p class="text-xs text-base-content/50">مدیر سیستم</p>
                    </div>
                </div>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content">
                    <i class="w-5 text-sm text-center fas fa-user"></i>
                    <span>ویرایش پروفایل</span>
                </a>
                <a href="#"
                    class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content">
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
        // Sidebar configuration from PHP
        window.sidebarConfig = {
            initialSidebarOpen: {{ $initialSidebarOpen ? 'true' : 'false' }},
            activeModuleKey: '{{ $activeModuleKey ?: '' }}',
            defaultModule: '{{ $defaultModule }}',
            isDirectLinkActive: {{ $isDirectLinkActive ? 'true' : 'false' }},
            notificationCount: {{ $notificationCount }},
            currentBranch: '{{ $currentBranch }}',
            modules: {
                @foreach ($modules as $module)
                    @if (!Arr::get($module, 'is_direct_link', false))
                        '{{ $module['key'] }}': '{{ $module['title'] }}',
                    @endif
                @endforeach
            }
        };

        // Initialize Alpine store for sidebar state (persistent across page navigations)
        function initSidebarStoreInline() {
            if (typeof Alpine === 'undefined') {
                document.addEventListener('alpine:init', initSidebarStoreInline);
                return;
            }

            // Use external sidebar store if available (from sidebar-store.js)
            if (typeof window.initSidebarStore === 'function') {
                window.initSidebarStore(window.sidebarConfig);
                return;
            }

            // Fallback: inline store initialization (should not be reached if JS is loaded)
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

                // Create modules object outside of Alpine store to avoid reactivity issues
                const modulesData = window.sidebarConfig?.modules || {};

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
                    activeModule: '{{ $activeModuleKey ?: $defaultModule }}',
                    activeMenu: '',
                    isDirectLinkActive: {{ $isDirectLinkActive ? 'true' : 'false' }},
                    notificationCount: {{ $notificationCount }},
                    currentBranch: savedCurrentBranch,
                    modules: modulesData,
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
                                this.activeModule = '{{ $activeModuleKey ?: $defaultModule }}';
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
                            this.activeModule = '{{ $activeModuleKey ?: $defaultModule }}';
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
                        return this.modules[this.activeModule] || 'داشبورد';
                    }
                });
            } else {
                // Update store values if they changed (e.g., after navigation)
                const store = Alpine.store('sidebar');
                const previousActiveModule = store.activeModule;
                store.activeModule = '{{ $activeModuleKey ?: $defaultModule }}';
                store.isDirectLinkActive = {{ $isDirectLinkActive ? 'true' : 'false' }};
                store.notificationCount = {{ $notificationCount }};
                // Update modules from window.sidebarConfig to avoid Alpine reactivity issues
                if (window.sidebarConfig?.modules) {
                    store.modules = window.sidebarConfig.modules;
                }

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
                // Reset activeModule to activeModuleKey if exists, otherwise use defaultModule
                store.activeModule = '{{ $activeModuleKey ?: $defaultModule }}';

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
            initSidebarStoreInline();
        } else {
            document.addEventListener('alpine:init', initSidebarStoreInline);
        }

        // Reload isPinned state after wire:navigate navigation
        // This is handled by setupSidebarPinnedReloader() in sidebar-store.js
        // No need to duplicate here if JS is loaded
    </script>
@endpush
