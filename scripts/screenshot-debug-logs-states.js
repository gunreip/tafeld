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

    // Create a browser context reusing storageState if available
    const contextOptions = { viewport: { width: 1280, height: 1024 } };
    if (fs.existsSync(storageFile)) {
        contextOptions.storageState = storageFile;
        console.log('Using existing Playwright storageState from', storageFile);
    } else {
        console.log('No existing storageState; starting unauthenticated context');
    }

    const context = await browser.newContext(contextOptions);
    const page = await context.newPage();

    const url = process.env.BASE_URL ?? 'http://127.0.0.1:8000';
    // Authenticate (if needed)
    const loginUrl = `${url}/login`;
    const target = `${url}/debug/logs`;

    // Try to login using seeded admin credentials if login form is present
    console.log('Checking auth by loading', target);
    await page.goto(target, { waitUntil: 'networkidle' });

    // If we were redirected to login, perform sign-in
    if ((await page.url()).includes('/login')) {
        console.log('Redirected to login — attempting sign-in');
        await page.goto(loginUrl, { waitUntil: 'networkidle' });
        // Wait for the email input
        const emailSelectorCandidates = ['input[name="email"]', 'input[type="email"]', 'input[wire\:model="email"]'];
        const passwordSelectorCandidates = ['input[name="password"]', 'input[type="password"]', 'input[wire\:model="password"]'];
        await page.waitForSelector('body');

        // Find the first selector that exists
        let emailSelector = 'input[type="email"]';
        for (const s of emailSelectorCandidates) {
            if (await page.$(s)) { emailSelector = s; break; }
        }
        let passwordSelector = 'input[type="password"]';
        for (const s of passwordSelectorCandidates) {
            if (await page.$(s)) { passwordSelector = s; break; }
        }

        if (await page.$(emailSelector)) {
            const user = process.env.E2E_USER ?? 'admin@example.com';
            const pass = process.env.E2E_PASS ?? 'ChangeMe123!';
            await page.fill(emailSelector, user);
            await page.fill(passwordSelector, pass);
            // Click submit (try localized button text, then fallback to button[type=submit])
            try {
                await page.click('button:has-text("Anmelden")');
            } catch (e1) {
                try {
                    await page.click('button:has-text("Log in")');
                } catch (e2) {
                    try { await page.click('button[type=submit]'); } catch (e3) { await page.$eval('form', f => f.submit()); }
                }
            }
            // Wait for navigation or for the URL to change away from /login
            await page.waitForTimeout(800);
            // allow any redirects to finish
            await page.waitForLoadState('networkidle');
            console.log('Sign-in attempt finished, now at', await page.url());

            // Save storage state so future runs are authenticated
            try {
                if (typeof context !== 'undefined') {
                    await context.storageState({ path: storageFile });
                    console.log('Saved Playwright storageState to', storageFile);
                }
            } catch (e) {
                console.log('Failed to save storageState:', e);
            }
        } else {
            console.log('No login inputs found on page; aborting auth attempt');
        }

        // Navigate to the target page after login attempt
        await page.goto(target, { waitUntil: 'networkidle' });
    } else {
        console.log('No redirect to login detected — proceeding');
    }

    await page.waitForSelector('body');
    await page.waitForTimeout(500);

    // Base state
    await page.screenshot({ path: `${outDir}/debug-logs-base.png`, fullPage: true });
    console.log('Saved base screenshot');

    // 1) Custom select: open dropdown
    const customTrigger = await page.$('.debug-custom-select-trigger');
    if (customTrigger) {
        await customTrigger.click();
        await page.waitForSelector('.debug-custom-select-panel', { state: 'visible', timeout: 2000 }).catch(() => { });
        await page.waitForTimeout(200);
        await page.screenshot({ path: `${outDir}/debug-logs-custom-open.png`, fullPage: true });
        console.log('Saved custom-select open screenshot');

        // Hover the first option to show left-accent stripe / hover state
        const firstOpt = await page.$('.debug-custom-select-panel ul li');
        if (firstOpt) {
            const box = await firstOpt.boundingBox();
            if (box) await page.mouse.move(box.x + box.width / 2, box.y + box.height / 2);
            await page.waitForTimeout(200);
            await page.screenshot({ path: `${outDir}/debug-logs-custom-hover.png`, fullPage: true });
            console.log('Saved custom-select hover screenshot');
        }

        // Select a non-first option (index 1) to enable clear button
        const options = await page.$$('.debug-custom-select-panel ul li');
        if (options && options.length > 1) {
            await options[1].click();
            await page.waitForTimeout(200);
            await page.screenshot({ path: `${outDir}/debug-logs-custom-selected.png`, fullPage: true });
            console.log('Saved custom-select selected screenshot');

            // Test: clicking the clear button should clear the field and keep focus in the trigger
            const clearBtn = await page.$('.ui-clear-button');
            if (clearBtn) {
                await clearBtn.click();
                await page.waitForTimeout(200);
                await page.screenshot({ path: `${outDir}/debug-logs-custom-click-clear.png`, fullPage: true });
                const activeAfterClickClear = await page.evaluate(() => {
                    const el = document.activeElement;
                    return el ? { tag: el.tagName, cls: el.className } : null;
                });
                console.log('Active element after clicking clear:', activeAfterClickClear);

                // Re-select option 1 to continue with the Tab/Escape tests
                await page.click('.debug-custom-select-trigger');
                await page.waitForSelector('.debug-custom-select-panel', { state: 'visible', timeout: 2000 }).catch(() => { });
                await page.waitForTimeout(120);
                await page.click('.debug-custom-select-panel ul li:nth-child(2)');
                await page.waitForTimeout(120);
            }

            // Test: pressing Tab should move focus outside the component (not to clear button)
            await page.keyboard.press('Tab');
            await page.waitForTimeout(120);
            await page.screenshot({ path: `${outDir}/debug-logs-custom-after-tab.png`, fullPage: true });
            const activeAfterTab = await page.evaluate(() => {
                const el = document.activeElement;
                return el ? { tag: el.tagName, cls: el.className } : null;
            });
            console.log('Active element after Tab:', activeAfterTab);

            // Test: pressing Escape when component is focused should clear selection
            await page.focus('.debug-custom-select-trigger');
            await page.keyboard.press('Escape');
            await page.waitForTimeout(120);
            await page.screenshot({ path: `${outDir}/debug-logs-custom-escape-clear.png`, fullPage: true });
            const activeAfterEscape = await page.evaluate(() => {
                const el = document.activeElement;
                return el ? { tag: el.tagName, cls: el.className } : null;
            });
            console.log('Active element after Escape:', activeAfterEscape);

            // Diagnostic: info about the trigger and clear button after pressing Escape
            const triggerInfo = await page.evaluate(() => {
                const t = document.querySelector('.debug-custom-select-trigger');
                const btn = document.querySelector('.ui-clear-button');
                return {
                    trigger: t ? { tag: t.tagName, cls: t.className, tabindex: t.getAttribute('tabindex'), visible: !!(t.offsetParent), label: t.querySelector('.debug-custom-select-trigger-label')?.textContent?.trim() ?? null } : null,
                    clearButton: btn ? { tag: btn.tagName, cls: btn.className, tabindex: btn.getAttribute('tabindex'), visible: !!(btn.offsetParent) } : null,
                };
            });
            console.log('Trigger/clear info after Escape:', triggerInfo);
        }
    }

    // 2) Suggest input: type to show suggestions and clear button
    const suggestField = await page.$('.debug-suggest-input-field');
    if (suggestField) {
        await suggestField.fill('a');
        await page.waitForTimeout(300);
        // wait for panel to appear
        await page.waitForSelector('.debug-suggest-input-panel', { state: 'visible', timeout: 2000 }).catch(() => { });
        await page.screenshot({ path: `${outDir}/debug-logs-suggest-open.png`, fullPage: true });
        console.log('Saved suggest open screenshot');

        // Hover the first suggest option
        const sfirst = await page.$('.debug-suggest-input-panel ul li');
        if (sfirst) {
            const box = await sfirst.boundingBox();
            if (box) await page.mouse.move(box.x + box.width / 2, box.y + box.height / 2);
            await page.waitForTimeout(150);
            await page.screenshot({ path: `${outDir}/debug-logs-suggest-hover.png`, fullPage: true });
        }

        // Ensure clear button visible
        await page.screenshot({ path: `${outDir}/debug-logs-suggest-with-clear.png`, fullPage: true });
    }

    // 3) Date range picker: open, apply preset to populate value, show clear
    const rangeTrigger = await page.$('.debug-range-input');
    if (rangeTrigger) {
        await rangeTrigger.click();
        await page.waitForSelector('.debug-range-panel', { state: 'visible', timeout: 2000 }).catch(() => { });
        await page.waitForTimeout(200);
        await page.screenshot({ path: `${outDir}/debug-logs-range-open.png`, fullPage: true });

        // Click the first preset button (Heute)
        const preset = await page.$('.debug-range-presets .preset-btn');
        if (preset) {
            await preset.click();
            await page.waitForTimeout(200);
            // Close the panel (click the trigger again)
            await rangeTrigger.click();
            await page.waitForTimeout(200);
            await page.screenshot({ path: `${outDir}/debug-logs-range-with-clear.png`, fullPage: true });
            console.log('Saved range with clear screenshot');
        }
    }

    await context.close();
    await browser.close();
    console.log('Done. Screenshots in', outDir);
})();
