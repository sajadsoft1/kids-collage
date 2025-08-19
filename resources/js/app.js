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

livewire_hot_reload();