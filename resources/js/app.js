// tafeld/resources/js/app.js
// console.log('[Tafeld-APP.JS] app.js LOADED');

// console.log('[Tafeld-APP.JS] IMPORT   ./bootstrap.js');
import './bootstrap';
// console.log('[Tafeld-APP.JS] IMPORTed ./bootstrap.js');

// Tafeld Debug-Modul
// console.log('[Tafeld-APP.JS] IMPORT   ./tafeld/debug.js');
import './tafeld/debug.js';
// console.log('[Tafeld-APP.JS] IMPORTED ./tafeld/debug.js');

// Charts für Debug-Dashboard
// console.log('[Tafeld-APP.JS] IMPORT   ./dashboard/charts.js');
import './dashboard/charts.js';
// console.log('[Tafeld-APP.JS] IMPORTed ./dashboard/charts.js');

// Nationalität, etc.
// console.log('[Tafeld-APP.JS] IMPORT   ./components/nationality.select.js');
import nationalitySelect from './components/nationality.select.js';
// console.log('[Tafeld-APP.JS] IMPORTED ./components/nationality.select.js');
window.nationalitySelect = nationalitySelect;

// Datepicker/Calendar
// console.log('[Tafeld-APP.JS] IMPORT   ./components/datepicker.js');
import datepickerComponent from './components/datepicker.js';
// console.log('[Tafeld-APP.JS] IMPORTED ./components/datepicker.js');
window.datepickerComponent = datepickerComponent;

// Livewire → JS Debug Event Bridge
// console.log('[Tafeld-APP.JS] addEventListener("livewire:init"');
document.addEventListener("livewire:init", () => {
    if (typeof Livewire !== 'undefined') {
        Livewire.on('tafeld-debug', (payload) => {
            if (window.TafeldDebug && typeof window.TafeldDebug.fromPHP === 'function') {
                window.TafeldDebug.fromPHP(payload);
            }
        });
    }
});
