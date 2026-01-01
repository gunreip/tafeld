This PR adds persistent Playwright storageState usage and CI support for automated screenshots.

Summary of changes:

- scripts/screenshot-debug-logs-states.js:
  - Reuses a saved Playwright storageState (default: ./playwright-storage/auth.json) to avoid repeated logins.
  - If redirected to /login, performs login (supports localized text) and saves storageState for future runs.
  - Configurable via environment variables: PLAYWRIGHT_STORAGE, E2E_USER, E2E_PASS, PLAYWRIGHT_HEADLESS.

- .gitignore: ignores playwright-storage/ (so session files are not committed).

- .github/workflows/playwright-e2e.yml:
  - Seeds an admin user (AdminSeeder) after migrations so the runner can log in.
  - Adds a job step that runs the debug screenshot script (`node scripts/screenshot-debug-logs-states.js`) and is executed with `if: always()` so screenshots are captured even when tests fail.
  - Uploads the `playwright-screenshots/` directory as artifacts along with existing Playwright artifacts.

Why:

This makes the debug screenshot script reliable in CI by preserving authentication between runs and provides visual artifacts in PR runs for easier review and debugging.

How to run locally:

- `node scripts/screenshot-debug-logs-states.js`
- Override credentials or storage with:
  - `E2E_USER=test@example.com E2E_PASS=Secret123 PLAYWRIGHT_STORAGE=./tmp/auth.json node scripts/screenshot-debug-logs-states.js`
- To run headed: `PLAYWRIGHT_HEADLESS=false node scripts/screenshot-debug-logs-states.js`

Notes:

- The storage file contains session cookies and must not be committed; `playwright-storage/` is ignored in `.gitignore`.
- CI can use repo secrets for `E2E_USER` and `E2E_PASS` (recommended) so the runner can sign in if needed.