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
import { setupSidebarPinnedReloader } from './components/sidebar/sidebar-store';

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
    // Hook into Livewire's request lifecycle
    Livewire.hook('request', ({ fail }) => {
        fail(({ status, content, preventDefault }) => {
            // Handle 500 errors
            if (status === 500) {
                preventDefault();

                // Parse error message
                let errorMessage = 'خطایی رخ داده است. لطفاً دوباره تلاش کنید.';
                try {
                    const data = JSON.parse(content);
                    if (data.message) {
                        errorMessage = data.message;
                    }
                } catch (e) {
                    // If parsing fails, use default message (silently in production)
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
                    const data = JSON.parse(content);
                    if (data.message) {
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
            component: component.name
        });
    });
});

Livewire.start();

// Setup sidebar pinned reloader
setupSidebarPinnedReloader();
