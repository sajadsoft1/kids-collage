import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import mask from '@alpinejs/mask'
import './bootstrap';
import './components.js';
import './../../vendor/power-components/livewire-powergrid/dist/powergrid';
import 'flatpickr';
import 'flatpickr/dist/l10n/fa.js';
import {livewire_hot_reload} from 'virtual:livewire-hot-reload'
import './components/multi-select';
import './livewire-datepicker-datepicker.js';
import { createFrestSidebar, isRouteActive } from './components/frest-sidebar';

// Export for global use
window.createFrestSidebar = createFrestSidebar;
window.isRouteActive = isRouteActive;

window.addEventListener('livewire:navigated', function () {
    console.log('navigated');
    // refactorPowergridDatatabel()
});

// function refactorPowergridDatatabel() {
//     document.querySelectorAll('[data-column="actions"]').forEach(element => element.removeAttribute('style'));
//     document.querySelectorAll('.powergrid-id').forEach(element => element.style.width = 0);
// }


Alpine.plugin(mask)
livewire_hot_reload();

// Global Livewire error handler
document.addEventListener('livewire:init', () => {
    // Hook into Livewire's request lifecycle (compatible with Livewire 3.6)
    Livewire.hook('request', ({ fail, respond }) => {
        // Handle request failures
        fail(({ status, content, preventDefault }) => {
            // Handle network failures (Failed to fetch)
            if (status === 0 || !status) {
                window.dispatchEvent(new CustomEvent('mary-toast', {
                    detail: {
                        toast: {
                            type: 'error',
                            title: 'خطای اتصال',
                            description: 'اتصال به سرور برقرار نشد. لطفاً اتصال اینترنت خود را بررسی کنید.',
                            css: 'alert-error',
                            icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-6 h-6 stroke-current shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                            timeout: 5000,
                            redirectTo: null
                        }
                    }
                }));
                return;
            }

            // Handle 500 errors
            if (status === 500) {
                preventDefault();

                // Parse error message
                let errorMessage = 'خطایی رخ داده است. لطفاً دوباره تلاش کنید.';
                try {
                    const data = typeof content === 'string' ? JSON.parse(content) : content;
                    if (data?.message) {
                        errorMessage = data.message;
                    }
                } catch (e) {
                    // If parsing fails, use default message (silently in production)
                }

                // Check if it's a method not found error
                if (errorMessage.includes('method') && errorMessage.includes('not found')) {
                    errorMessage = 'عملیات درخواستی در دسترس نیست. لطفاً صفحه را رفرش کنید.';
                }

                // Dispatch toast event with correct MaryUI structure
                window.dispatchEvent(new CustomEvent('mary-toast', {
                    detail: {
                        toast: {
                            type: 'error',
                            title: 'خطا',
                            description: errorMessage,
                            css: 'alert-error',
                            icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-6 h-6 stroke-current shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                            timeout: 5000,
                            redirectTo: null
                        }
                    }
                }));

                // Log to console in development
                console.error('Livewire Error:', errorMessage);
            }

            // Handle 419 (CSRF token mismatch / session expired)
            if (status === 419) {
                preventDefault();
                window.dispatchEvent(new CustomEvent('mary-toast', {
                    detail: {
                        toast: {
                            type: 'warning',
                            title: 'هشدار',
                            description: 'نشست شما منقضی شده است. لطفاً صفحه را رفرش کنید.',
                            css: 'alert-warning',
                            icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-6 h-6 stroke-current shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
                            timeout: 5000,
                            redirectTo: null
                        }
                    }
                }));
            }

            // Handle 403 (Forbidden)
            if (status === 403) {
                preventDefault();
                window.dispatchEvent(new CustomEvent('mary-toast', {
                    detail: {
                        toast: {
                            type: 'warning',
                            title: 'دسترسی ممنوع',
                            description: 'شما اجازه انجام این عملیات را ندارید.',
                            css: 'alert-warning',
                            icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-6 h-6 stroke-current shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
                            timeout: 5000,
                            redirectTo: null
                        }
                    }
                }));
            }

            // Handle 400 (Bad Request)
            if (status === 400) {
                preventDefault();

                // Parse error message
                let errorMessage = 'درخواست نامعتبر است. لطفاً دوباره تلاش کنید.';
                try {
                    const data = typeof content === 'string' ? JSON.parse(content) : content;
                    if (data?.message) {
                        errorMessage = data.message;
                    }
                } catch (e) {
                    // If parsing fails, use default message
                }

                window.dispatchEvent(new CustomEvent('mary-toast', {
                    detail: {
                        toast: {
                            type: 'error',
                            title: 'درخواست نامعتبر',
                            description: errorMessage,
                            css: 'alert-error',
                            icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-6 h-6 stroke-current shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                            timeout: 5000,
                            redirectTo: null
                        }
                    }
                }));
            }
        });
    });

    // Handle message processing errors
    Livewire.hook('message.failed', (message, component) => {
        console.error('Livewire message failed:', {
            message,
            component: component?.name || 'unknown'
        });
    });
});

Livewire.start();

// Setup sidebar pinned reloader (only if sidebar store is available)
// This is only needed for layouts that use the sidebar store (e.g., tow-step, metronic)
// We use dynamic import to avoid loading sidebar-store.js for layouts that don't need it
(function setupSidebarReloaderIfNeeded() {
    // Check if Alpine is available
    if (typeof Alpine === 'undefined') {
        // Wait for Alpine to be available
        document.addEventListener('alpine:init', setupSidebarReloaderIfNeeded, { once: true });
        return;
    }

    // Dynamically import sidebar store only if needed
    import('./components/sidebar/sidebar-store').then((module) => {
        if (typeof module.setupSidebarPinnedReloader === 'function') {
            // Check if sidebar store exists (it will be initialized by the sidebar component)
            // Wait a bit for the sidebar component to initialize the store
            let attempts = 0;
            const maxAttempts = 10; // Maximum 2 seconds (10 * 200ms)

            const checkAndSetup = () => {
                if (Alpine.store && Alpine.store('sidebar')) {
                    module.setupSidebarPinnedReloader();
                } else if (attempts < maxAttempts) {
                    attempts++;
                    // Check again after a short delay
                    setTimeout(checkAndSetup, 200);
                }
                // If max attempts reached and store doesn't exist, silently give up
                // This means the layout doesn't use the sidebar store (e.g., app layout)
            };

            // Start checking after a short delay
            setTimeout(checkAndSetup, 100);
        }
    }).catch(() => {
        // Sidebar store not available for this layout (e.g., app layout), silently ignore
        // This is expected behavior for layouts that don't use the sidebar store
    });
})();
