import { chromium } from 'playwright';

(async () => {
    const browser = await chromium.launch();
    const page = await browser.newPage();
    await page.goto('http://127.0.0.1:8000/_debug_test/overview', { waitUntil: 'load' });

    const info = await page.evaluate(() => {
        const canvas = document.querySelector('#chart-logs-level');
        return {
            chartExists: typeof (window).Chart !== 'undefined',
            chartGetChartIsFunc: typeof (window).Chart?.getChart === 'function',
            chartGetChartResult: (window).Chart?.getChart(canvas) ? true : false,
            canvasDatasetInitialized: canvas?.dataset?.chartInitialized ?? null,
            canvasData: canvas?.dataset?.chart ?? null,
            windowRegistryExists: !!(window).__tafeld_chart_registry,
        };
    });

    console.log('EVAL INFO:', info);

    await browser.close();
})();