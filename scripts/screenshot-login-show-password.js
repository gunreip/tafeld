import { chromium } from 'playwright';
import fs from 'fs';

(async () => {
    const outDir = './playwright-screenshots';
    if (!fs.existsSync(outDir)) fs.mkdirSync(outDir, { recursive: true });

    const storageFile = process.env.PLAYWRIGHT_STORAGE ?? './playwright-storage/auth.json';
    const storageDir = storageFile.replace(/\/[^^\/]+$/, '') || './playwright-storage';
    if (!fs.existsSync(storageDir)) fs.mkdirSync(storageDir, { recursive: true });

    const headless = process.env.PLAYWRIGHT_HEADLESS !== 'false';
    const browser = await chromium.launch({ headless });

    const contextOptions = { viewport: { width: 1280, height: 1024 } };
    if (fs.existsSync(storageFile)) {
        contextOptions.storageState = storageFile;
        console.log('Using existing Playwright storageState from', storageFile);
    }

    const context = await browser.newContext(contextOptions);
    const page = await context.newPage();

    const url = process.env.BASE_URL ?? 'http://127.0.0.1:8000';
    const loginUrl = `${url}/login`;

    await page.goto(loginUrl, { waitUntil: 'networkidle' });
    await page.waitForSelector('body');

    // Ensure the password input is present
    const passwordSelectorCandidates = ['input[name="password"]', 'input[type="password"]', 'input[wire\\:model="password"]'];
    let passwordSelector = 'input[type="password"]';
    for (const s of passwordSelectorCandidates) {
        if (await page.$(s)) { passwordSelector = s; break; }
    }

    if (!(await page.$(passwordSelector))) {
        console.log('No password input found on login page; aborting');
        await context.close();
        await browser.close();
        return;
    }

    // Fill password and take a screenshot (masked)
    const sample = 'SeKr3tPW!';
    await page.fill(passwordSelector, sample);
    await page.waitForTimeout(120);
    await page.screenshot({ path: `${outDir}/login-password-hidden.png`, fullPage: true });
    console.log('Saved login-password-hidden.png');

    // Find the toggle button near the password input
    const toggleBtn = await page.$(`${passwordSelector} ~ span button[type="button"]`);
    if (!toggleBtn) {
        console.log('No show/hide toggle button found next to password input; aborting');
        await context.close();
        await browser.close();
        return;
    }

    // Click to show password
    await toggleBtn.click();
    await page.waitForTimeout(120);

    const afterType = await page.$eval(passwordSelector, (el) => el.type);
    const afterVal = await page.$eval(passwordSelector, (el) => el.value);
    console.log('After clicking toggle -> type:', afterType, 'value len:', afterVal.length);

    await page.screenshot({ path: `${outDir}/login-password-visible.png`, fullPage: true });
    console.log('Saved login-password-visible.png');

    // Click to hide again
    // try to find button by aria-pressed or aria-label change
    await toggleBtn.click();
    await page.waitForTimeout(120);

    const finalType = await page.$eval(passwordSelector, (el) => el.type);
    console.log('After clicking toggle again -> type:', finalType);

    await page.screenshot({ path: `${outDir}/login-password-hidden-after.png`, fullPage: true });
    console.log('Saved login-password-hidden-after.png');

    await context.close();
    await browser.close();
    console.log('Done. Screenshots in', outDir);
})();