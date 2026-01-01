import { test, expect } from '@playwright/test';

const BASE = process.env.BASE_URL ?? 'http://127.0.0.1:8000';
const URL = `${BASE}/_debug_test/overview`;

// Increase global test timeout to allow for slower CI environments
test.setTimeout(120000);

test.describe('Debug Overview E2E', () => {
  let consoleLogs: string[] = []; 

  test.beforeEach(async ({ page }) => {
    consoleLogs = [];
    page.on('console', (m) => consoleLogs.push(`${m.type()}: ${m.text()}`));
  });

  test.afterEach(async ({ page }, testInfo) => {
    if (testInfo.status !== 'passed') {
      await testInfo.attach('console.log', {
        body: consoleLogs.join('\n'),
        contentType: 'text/plain',
      });

      await testInfo.attach('page.html', {
        body: await page.content(),
        contentType: 'text/html',
      });

      await testInfo.attach('screenshot.png', {
        body: await page.screenshot(),
        contentType: 'image/png',
      });
    }
  });

  test('charts initialize and Chart.js instance exists', async ({ page }) => {
    await page.goto(URL, { waitUntil: 'domcontentloaded' });

    // Wait for Livewire/JS to initialise and charts to be present
    await page.waitForSelector('canvas[data-chart]', { timeout: 60000 });

    // Ensure Chart.js is loaded and Chart instance exists for canvas
    const exists = await page.evaluate(() => {
      const canvas = document.querySelector('#chart-logs-level');
      if (!canvas) return false;
      // Chart.getChart is available in Chart.js v4
      // @ts-ignore
      const chart = typeof (window as any).Chart?.getChart === 'function'
        ? (window as any).Chart.getChart(canvas as HTMLCanvasElement)
        : null;
      return !!chart;
    });

    expect(exists).toBeTruthy();
  });
});
