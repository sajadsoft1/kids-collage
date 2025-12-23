/**
 * Sidebar Alpine.js Store
 * Manages sidebar state including open/close, pin/unpin, active modules, and device detection
 */
import SidebarStorage from './sidebar-storage.js';

export function createSidebarStore(config) {
    const {
        initialSidebarOpen = false,
        activeModuleKey = '',
        defaultModule = '',
        isDirectLinkActive = false,
        notificationCount = 0,
        currentBranch = 'شعبه مرکزی',
        modules = {},
    } = config;

    // Load initial values from storage
    const savedSidebarOpen = SidebarStorage.get('sidebarOpen', initialSidebarOpen, true);
    const savedCurrentBranch = SidebarStorage.get('currentBranch', currentBranch, false) || currentBranch;
    const savedIsPinned = SidebarStorage.get('sidebarPinned', true, false);

    // Initialize currentBranch if not exists
    if (!SidebarStorage.get('currentBranch', null, false)) {
        SidebarStorage.set('currentBranch', savedCurrentBranch, false);
    }

    return {
        get sidebarOpen() {
            return this._sidebarOpen;
        },
        set sidebarOpen(value) {
            this._sidebarOpen = value;
            SidebarStorage.set('sidebarOpen', value, true);
        },
        get isPinned() {
            return this._isPinned;
        },
        set isPinned(value) {
            this._isPinned = value;
            SidebarStorage.set('sidebarPinned', value, false);
        },
        menuVisible: false,
        activeModule: activeModuleKey || defaultModule,
        activeMenu: '',
        isDirectLinkActive: isDirectLinkActive,
        notificationCount: notificationCount,
        currentBranch: savedCurrentBranch,
        modules: modules,
        isMobile: false,
        isTablet: false,
        init() {
            // Initialize _sidebarOpen and _isPinned
            this._sidebarOpen = savedSidebarOpen;
            this._isPinned = savedIsPinned;
            this.updateDeviceType();

            // Store resize handler reference for cleanup
            this._resizeHandler = () => {
                this.updateDeviceType();
            };

            // Listen for resize with debouncing
            window.addEventListener('resize', this._resizeHandler);

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
        destroy() {
            // Cleanup event listeners
            if (this._resizeHandler) {
                window.removeEventListener('resize', this._resizeHandler);
                this._resizeHandler = null;
            }
        },
        toggleSidebar() {
            // On mobile, toggle both level 1 and level 2 together
            if (this.isMobile) {
                this.menuVisible = !this.menuVisible;
                this.sidebarOpen = this.menuVisible;
                // Set default module if none is active when opening
                if (this.menuVisible && this.activeModule === '') {
                    this.activeModule = activeModuleKey || defaultModule;
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
                this.activeModule = activeModuleKey || defaultModule;
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
            // Close sidebar completely on mobile/tablet (both level 1 and level 2)
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
            return modules[this.activeModule] || 'داشبورد';
        },
    };
}

/**
 * Initialize sidebar store with Alpine.js
 * Expose to window for Blade template access
 */
export function initSidebarStore(config) {
    if (typeof Alpine === 'undefined') {
        document.addEventListener('alpine:init', () => initSidebarStore(config));
        return;
    }

    if (!Alpine.store('sidebar')) {
        Alpine.store('sidebar', createSidebarStore(config));
    } else {
        // Update store values if they changed (e.g., after navigation)
        const store = Alpine.store('sidebar');
        store.activeModule = config.activeModuleKey || config.defaultModule;
        store.isDirectLinkActive = config.isDirectLinkActive;
        store.notificationCount = config.notificationCount;
        store.modules = config.modules || {};

        // Reload isPinned from localStorage to ensure it persists across wire:navigate
        const pinValue = SidebarStorage.get('sidebarPinned', true, false);
        store._isPinned = pinValue;
        if (store.isPinned !== pinValue) {
            store.isPinned = pinValue;
        }

        // Sync state after navigation
        store.activeModule = config.activeModuleKey || config.defaultModule;
        if (typeof store.syncStateAfterNavigation === 'function') {
            store.syncStateAfterNavigation();
        }
    }
}

// Expose to window for Blade template access
window.initSidebarStore = initSidebarStore;

/**
 * Reload sidebar pinned state after navigation
 */
let reloaderTimeout = null;
let alpineNavigatedHandler = null;

export function setupSidebarPinnedReloader() {
    if (window.__sidebarPinnedReloader) {
        return;
    }

    window.__sidebarPinnedReloader = () => {
        // Clear previous timeout if exists
        if (reloaderTimeout) {
            clearTimeout(reloaderTimeout);
            reloaderTimeout = null;
        }

        if (typeof Alpine === 'undefined' || !Alpine.store('sidebar')) {
            return;
        }

        const pinValue = SidebarStorage.get('sidebarPinned', true, false);
        const store = Alpine.store('sidebar');

        if (store.isPinned !== pinValue) {
            store.isPinned = pinValue;
            if (typeof store.syncStateAfterPinChange === 'function') {
                store.syncStateAfterPinChange();
            }
        }
    };

    // Add event listeners only once
    alpineNavigatedHandler = () => {
        if (reloaderTimeout) {
            clearTimeout(reloaderTimeout);
        }
        reloaderTimeout = setTimeout(() => {
            window.__sidebarPinnedReloader();
            reloaderTimeout = null;
        }, 50);
    };

    const livewireMorphHandler = () => {
        if (reloaderTimeout) {
            clearTimeout(reloaderTimeout);
        }
        reloaderTimeout = setTimeout(() => {
            window.__sidebarPinnedReloader();
            reloaderTimeout = null;
        }, 50);
    };

    document.addEventListener('alpine:navigated', alpineNavigatedHandler, { once: false });

    // Also reload when Livewire finishes updating
    if (typeof Livewire !== 'undefined') {
        Livewire.hook('morph.updated', livewireMorphHandler);
    }

    // Cleanup function (can be called if needed)
    window.__cleanupSidebarPinnedReloader = () => {
        if (reloaderTimeout) {
            clearTimeout(reloaderTimeout);
            reloaderTimeout = null;
        }
        if (alpineNavigatedHandler) {
            document.removeEventListener('alpine:navigated', alpineNavigatedHandler);
            alpineNavigatedHandler = null;
        }
        // Note: Livewire hooks cannot be removed, but they won't cause leaks
        // since they're cleaned up when the page unloads
    };
}
