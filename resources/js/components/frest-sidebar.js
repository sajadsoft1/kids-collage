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

/**
 * Create Frest Sidebar Alpine.js component
 * @param {Object} config - Configuration object
 * @returns {Object} Alpine.js component data
 */
export function createFrestSidebar(config = {}) {
    return {
        collapsed: config.collapsed || localStorage.getItem('frest_sidebar_collapsed') === 'true',

        init() {
            // Watch for sidebar collapse changes and save to localStorage
            this.$watch('collapsed', (value) => {
                localStorage.setItem('frest_sidebar_collapsed', value.toString());
            });

            // Keyboard navigation support
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
        },

        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('frest_sidebar_collapsed', this.collapsed.toString());
            this.$dispatch('sidebar-collapse', { collapsed: this.collapsed });
        },

        isRouteActive(url, exact = false) {
            return isRouteActive(url, exact);
        }
    };
}

