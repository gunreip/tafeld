import './bootstrap';

// Material-Color
// import { argbFromHex, hexFromArgb } from "@materialx/material-color-utilities";
// import { MaterialDynamicColors } from "@materialx/material-color-utilities";
// const MATERIAL_DYNAMIC_COLORS = new MaterialDynamicColors();
// const allDynamicColors = MATERIAL_DYNAMIC_COLORS.allDynamicColors;

// for(const dynamicColor of allDynamicColors) {
//   console.log(dynamicColor.name);
// }

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

import debugSuggestInput from './ui/debug-suggest-input.js';
window.debugSuggestInput = debugSuggestInput;

import { debugRangeDatepicker } from './ui/debug-range-datepicker.js';
window.debugRangeDatepicker = debugRangeDatepicker;

// // Date Range (Flatpickr PoC)
// import { debugRangeDatepickerFlatpickr } from './ui/debug-range-datepicker-flatpickr.js';
// window.debugRangeDatepickerFlatpickr = debugRangeDatepickerFlatpickr;

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
