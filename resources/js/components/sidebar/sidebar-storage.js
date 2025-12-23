/**
 * Sidebar Storage Service
 * Centralized storage management for sidebar state
 * Handles localStorage and sessionStorage with error handling
 */
class SidebarStorage {
    /**
     * Get value from storage
     * @param {string} key - Storage key
     * @param {*} defaultValue - Default value if key doesn't exist
     * @param {boolean} useSession - Use sessionStorage instead of localStorage
     * @returns {*}
     */
    static get(key, defaultValue, useSession = false) {
        const storage = useSession ? sessionStorage : localStorage;
        try {
            const value = storage.getItem(key);
            if (value === null) {
                return defaultValue;
            }
            // Handle boolean values
            if (value === 'true') {
                return true;
            }
            if (value === 'false') {
                return false;
            }
            return value;
        } catch (e) {
            // Storage not available (private browsing, etc.)
            return defaultValue;
        }
    }

    /**
     * Set value in storage
     * @param {string} key - Storage key
     * @param {*} value - Value to store
     * @param {boolean} useSession - Use sessionStorage instead of localStorage
     */
    static set(key, value, useSession = false) {
        const storage = useSession ? sessionStorage : localStorage;
        try {
            storage.setItem(key, value.toString());
        } catch (e) {
            // Storage not available, silently fail
        }
    }

    /**
     * Remove value from storage
     * @param {string} key - Storage key
     * @param {boolean} useSession - Use sessionStorage instead of localStorage
     */
    static remove(key, useSession = false) {
        const storage = useSession ? sessionStorage : localStorage;
        try {
            storage.removeItem(key);
        } catch (e) {
            // Storage not available, silently fail
        }
    }
}

export default SidebarStorage;

