// tafeld/resources/js/dashboard/charts.js

import Chart from "chart.js/auto";

// Ensure Chart is available globally for runtime access and E2E tests that
// expect `window.Chart.getChart` to exist.
if (typeof window !== "undefined" && !(window).Chart) {
    (window).Chart = Chart;
}

// Globale Registry: jedem Canvas ist genau ein Chart zugeordnet
const chartRegistry = new Map();

/**
 * Farbpalette abhängig vom Darkmode.
 */
function getColors() {
    const dark = document.documentElement.classList.contains("dark");

    return {
        text: dark ? "#e5e7eb" : "#111827",
        grid: dark ? "#374151" : "#e5e7eb",
        bar: [
            "#60a5fa", // blau
            "#34d399", // grün
            "#fbbf24", // gelb
            "#f87171", // rot
            "#a78bfa", // lila
            "#f472b6", // pink
        ],
    };
}

/**
 * Erstellt oder ersetzt einen Bar-Chart für ein Canvas.
 */
function renderChart(canvas, data) {
    // Vorhandenen Chart sauber zerstören
    if (chartRegistry.has(canvas)) {
        // console.log("[charts.js] renderChart(): Destroy alter Chart für", canvas.id);
        chartRegistry.get(canvas).destroy();
        chartRegistry.delete(canvas);
    }

    // console.log("[charts.js] renderChart(): BEFORE", canvas.id, {
    //     styleHeight: canvas.style.height,
    //     offsetHeight: canvas.offsetHeight,
    //     clientHeight: canvas.clientHeight,
    //     scrollHeight: canvas.scrollHeight,
    //     parentOffsetHeight: canvas.parentElement?.offsetHeight,
    // });

    // Fallback-Höhe, falls nicht via CSS/Blade gesetzt
    if (!canvas.style.height) {
        // console.log("[charts.js] renderChart(): Fallback-Höhe gesetzt (350px) für", canvas.id);
        canvas.style.height = "350px";
    }

    // Canvas-Größe basierend auf dem finalen Parent-Container setzen
    const parentWidth = canvas.parentElement?.clientWidth || canvas.offsetWidth || 600;
    canvas.width = parentWidth;
    canvas.height = 350;

    const ctx = canvas.getContext("2d");
    const colors = getColors();

    const labels = data.map((e) => e.label);
    const values = data.map((e) => e.value);

    const chart = new Chart(ctx, {
        type: "bar",
        data: {
            labels,
            datasets: [
                {
                    label: "",
                    data: values,
                    backgroundColor: colors.bar,
                },
            ],
        },
        options: {
            responsive: false,             // verhindert Livewire-Resize-Loop
            maintainAspectRatio: false,
            animation: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                x: {
                    ticks: { color: colors.text },
                    grid: { color: colors.grid },
                },
                y: {
                    ticks: { color: colors.text },
                    grid: { color: colors.grid },
                },
            },
        },
    });

    chartRegistry.set(canvas, chart);

    // Mark the canvas as initialized and expose registry for tests/debugging.
    try {
        canvas.dataset.chartInitialized = '1';
    } catch (e) {
        // ignore in case of readonly dataset
    }

    // Expose registry for debugging / E2E tests
    try {
        window.__tafeld_chart_registry = chartRegistry;
    } catch (e) {
        // ignore in SSR environments
    }

    // console.log("[charts.js] renderChart(): AFTER", canvas.id, {
    //     offsetHeight: canvas.offsetHeight,
    //     clientHeight: canvas.clientHeight,
    //     scrollHeight: canvas.scrollHeight,
    //     styleHeight: canvas.style.height,
    //     parentOffsetHeight: canvas.parentElement?.offsetHeight,
    //     chartInstance: chart,
    // });
}

/**
 * Initialisiert alle Charts auf der aktuellen Seite.
 */
function initCharts() {
    const canvases = document.querySelectorAll("canvas[data-chart]");
    // console.log("[charts.js] initCharts(): canvases gefunden =", canvases.length);

    canvases.forEach((canvas) => {
        let data;

        try {
            data = JSON.parse(canvas.dataset.chart || "[]");
        } catch {
            // console.log("[charts.js] initCharts(): JSON parse ERROR bei Canvas", canvas.id);
            return;
        }

        if (!Array.isArray(data) || data.length === 0) {
            // console.log("[charts.js] initCharts(): Canvas", canvas.id, "hat leere Daten → SKIP");
            return;
        }

        // console.log("[charts.js] initCharts(): Render Canvas", canvas.id, "mit data =", data);
        renderChart(canvas, data);
    });
}

/**
 * Livewire-Events
 */
document.addEventListener("livewire:load", () => {
    // console.log("[charts.js] Event: livewire:load");
    initCharts();
});

document.addEventListener("livewire:navigated", () => {
    // console.log("[charts.js] Event: livewire:navigated");
    initCharts();
});

document.addEventListener("tafeld-debug-chart-refresh", () => {
    // console.log("[charts.js] Event: tafeld-debug-chart-refresh");
    initCharts();
});

// Also attempt initialization immediately once the script loads. This handles
// cases where a chart-refresh event fired before our listeners were set up
// (e.g., server-side Livewire dispatch during initial render). We try to
// initialize right away if the DOM is ready, otherwise wait for DOMContentLoaded.
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    // console.log('[charts.js] initCharts(): immediate init (document ready)');
    initCharts();
} else {
    document.addEventListener('DOMContentLoaded', () => {
        // console.log('[charts.js] initCharts(): DOMContentLoaded init');
        initCharts();
    });
}
