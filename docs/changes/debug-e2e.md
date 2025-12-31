## 2025-12-31 - e2e/debug: ensure built assets load for debug test

- Ensure built Vite assets are included for test-only debug page so Playwright E2E sees and initializes Chart.js charts.
- Add Playwright diagnostic attachments (console/page.html/screenshot) on failures.
