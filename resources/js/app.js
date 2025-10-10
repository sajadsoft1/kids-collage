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
                    // If parsing fails, use default message
                    console.error('Error parsing response:', e);
                }

                // Dispatch toast event
                window.dispatchEvent(new CustomEvent('mary-toast', {
                    detail: {
                        type: 'error',
                        title: 'خطا',
                        description: errorMessage,
                        position: 'toast-top toast-center',
                        timeout: 5000,
                        redirectTo: null
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
                        type: 'warning',
                        title: 'هشدار',
                        description: 'نشست شما منقضی شده است. لطفاً صفحه را رفرش کنید.',
                        position: 'toast-top toast-center',
                        timeout: 5000,
                        redirectTo: null
                    }
                }));
            }

            // Handle 403 (Forbidden)
            if (status === 403) {
                preventDefault();
                window.dispatchEvent(new CustomEvent('mary-toast', {
                    detail: {
                        type: 'warning',
                        title: 'دسترسی ممنوع',
                        description: 'شما اجازه انجام این عملیات را ندارید.',
                        position: 'toast-top toast-center',
                        timeout: 5000,
                        redirectTo: null
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
