/**
 * Frest Sidebar Alpine.js Component
 * Provides route checking and sidebar state management
 */

/**
 * Check if a route is active
 * @param {string} url - The URL to check
 * @param {boolean} exact - Whether to check exact match
 * @returns {boolean}
 */
export function isRouteActive(url, exact = false) {
    if (!url || url === '#') return false;

    try {
        const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
        const targetUrl = new URL(url, window.location.origin);
        let targetPath = targetUrl.pathname.replace(/\/+$/, '') || '/';

        if (exact) {
            // Exact match: paths must be identical
            return currentPath === targetPath;
        }

        // Non-exact match
        if (currentPath === targetPath) return true;
        if (targetPath === '/') return false;
        
        // Check if current path starts with target path
        return currentPath.startsWith(targetPath + '/') || currentPath === targetPath;
    } catch (e) {
        // If URL parsing fails, fallback to simple string comparison
        console.warn('isRouteActive: Failed to parse URL', url, e);
        return false;
    }
}

/**
 * Create Frest Sidebar Alpine.js component
 * @param {Object} config - Configuration object
 * @returns {Object} Alpine.js component data
 */
export function createFrestSidebar(config = {}) {
    return {
        collapsed: config.collapsed || localStorage.getItem('frest_sidebar_collapsed') === 'true',
        isMobile: false,

        init() {
            // Detect if this is mobile sidebar
            this.isMobile = this.$el.hasAttribute('data-frest-sidebar') && 
                           this.$el.getAttribute('data-frest-sidebar') === 'mobile';

            // Mobile sidebar should never be collapsed
            if (this.isMobile) {
                this.collapsed = false;
            }

            // Watch for sidebar collapse changes and save to localStorage (desktop only)
            if (!this.isMobile) {
                this.$watch('collapsed', (value) => {
                    localStorage.setItem('frest_sidebar_collapsed', value.toString());
                });
            }

            // Listen for collapse events from desktop sidebar (for mobile sync)
            if (this.isMobile) {
                this.$el.addEventListener('sidebar-collapse', (e) => {
                    // Mobile sidebar doesn't collapse, but we can use this for other purposes
                });
            }

            // Keyboard navigation support (desktop only)
            if (!this.isMobile) {
                this.$el.addEventListener('keydown', (e) => {
                    // Handle Escape key to close submenus
                    if (e.key === 'Escape') {
                        const openSubmenu = this.$el.querySelector('[aria-expanded="true"]');
                        if (openSubmenu) {
                            openSubmenu.click();
                            openSubmenu.focus();
                        }
                    }
                });
            }

            // Handle window resize to sync mobile/desktop state
            this.handleResize = () => {
                const isLg = window.matchMedia('(min-width: 1024px)').matches;
                if (this.isMobile && isLg) {
                    // If mobile sidebar is open and we resize to desktop, close mobile
                    const body = document.body;
                    if (body) {
                        const bodyData = Alpine.$data(body);
                        if (bodyData && bodyData.sidebarOpen) {
                            bodyData.sidebarOpen = false;
                        }
                    }
                }
            };

            window.addEventListener('resize', this.handleResize);
        },

        toggle() {
            // Mobile sidebar doesn't toggle collapse
            if (this.isMobile) {
                return;
            }

            this.collapsed = !this.collapsed;
            localStorage.setItem('frest_sidebar_collapsed', this.collapsed.toString());
            this.$dispatch('sidebar-collapse', { collapsed: this.collapsed });
        },

        isRouteActive(url, exact = false) {
            return isRouteActive(url, exact);
        }
    };
}

