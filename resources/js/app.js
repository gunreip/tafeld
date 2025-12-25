import './bootstrap';

// Tafeld Debug-Modul
import './ui/debug.js';

// Charts für Debug-Dashboard
import './ui/dashboard-charts.js';

// Nationalität, etc.
import nationalitySelect from './ui/nationality-select.js';
window.nationalitySelect = nationalitySelect;

// Datepicker/Calendar
import datepickerComponent from './ui/datepicker.js';
window.datepickerComponent = datepickerComponent;

import debugCustomSelect from './ui/debug-custom-select.js';
window.debugCustomSelect = debugCustomSelect;

// Livewire → JS Debug Event Bridge
document.addEventListener("livewire:init", () => {
    if (typeof Livewire !== 'undefined') {
        Livewire.on('tafeld-debug', (payload) => {
            if (window.TafeldDebug && typeof window.TafeldDebug.fromPHP === 'function') {
                window.TafeldDebug.fromPHP(payload);
            }
        });
    }
});
