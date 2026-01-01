import { chromium } from 'playwright';
import fs from 'fs';

(async () => {
    const outDir = './playwright-screenshots';
    if (!fs.existsSync(outDir)) fs.mkdirSync(outDir, { recursive: true });
    const outPath = `${outDir}/debug-logs.png`;

    const browser = await chromium.launch();
    const page = await browser.newPage({ viewport: { width: 1280, height: 1024 } });

    const url = process.env.BASE_URL ?? 'http://127.0.0.1:8000';
    const target = `${url}/debug/logs`;
    console.log('Navigating to', target);

    await page.goto(target, { waitUntil: 'networkidle' });

    // Wait for page body and a sensible UI element
    await page.waitForSelector('body');
    // Small delay to let Alpine/JS apply UI states
    await page.waitForTimeout(500);

    await page.screenshot({ path: outPath, fullPage: true });
    console.log('Saved screenshot to', outPath);

    await browser.close();
})();
