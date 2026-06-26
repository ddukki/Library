# ADR-0017: Frontend Testing Strategy — Pest Feature Tests + Playwright

**Status:** Approved (refined — Dusk → Playwright after implementation)
**Date:** 2026-06-22
**Replaces:** None
**Supersedes:** None

## Context

The app had Vue 2 components incrementally migrated to Alpine.js (ADR-0016). Each migration involved Blade partials, Alpine JS modules, and AJAX interactions with Laravel API endpoints.

Previously, the only verification was manual — the developer clicks buttons and observes behavior. The shelve button bug (Spec 011) is a concrete case where:
1. The server-side endpoint (`EditionController@shelve`) works correctly
2. The Alpine JS module constructs the correct request
3. But the bug was in type coercion — `===` vs `==` — only visible at the browser level

This reveals two testing gaps:
- **Server-side**: No automated tests that API endpoints return correct responses
- **Browser-side**: No automated tests that the full stack (DOM + JS + HTTP + PHP) works end-to-end

Which testing tools should we adopt to catch both categories of bugs without requiring manual testing?

## Decision Drivers

- Must catch both server-side API errors and browser-level interaction bugs
- Must integrate with existing Docker dev environment (no host PHP)
- Must not add excessive CI complexity for a single-developer project
- Must be practical for incremental adoption — write tests alongside migrations
- Must work with Alpine.js (Vue 2 migration target) and AJAX interactions

## Considered Options

### A. Pest Feature Tests + Playwright

Two layers:
1. **Pest HTTP tests** — call API endpoints, verify JSON responses, status codes, and database state. Fast (~50ms per test), no browser needed. Uses Pest 4 with the Laravel plugin, runs inside Docker.
2. **Playwright** — opens headless Chromium, clicks buttons, fills forms, asserts DOM content. Tests the full stack from browser to database. Slower (~2-5s per test) but catches real interaction bugs. Runs on host Node.js against the Docker dev server.

Playwright provides excellent debugging (trace viewer, screenshots, video), auto-waiting, and a rich assertion API.

```
docker exec lib-dev php ./vendor/bin/pest  # API tests (fast)
npx playwright test                        # Browser tests (slow)
```

### B. Pest Feature Tests + Laravel Dusk

Pest for API tests, Dusk for browser E2E running inside Docker.

Dusk is Laravel-native: uses the same test assertions as PHPUnit, supports `RefreshDatabase`, and runs inside the existing Docker container.

**Pros:**
- Tighter Laravel integration
- Runs in Docker alongside PHP
- Shares database/reset helpers

**Cons vs Playwright:**
- Requires Chrome + ChromeDriver in Docker (setup burden)
- ChromeDriver version drift (must match Chrome version)
- Less mature debugging (no trace viewer, time-travel)
- Browser tests are PHP files — conceptually different from JS-based Alpine stack
- No auto-waiting — explicit `$browser->waitFor()` needed

### C. Pest Feature Tests + Cypress

Pest for API tests, Cypress for browser E2E.

Cypress is a standalone JS framework: installs via npm, opens its own browser (Electron), uses JS assertions. Good developer experience (time-travel, auto-waiting, network stubbing).

**Pros:**
- Excellent debugging DX
- Network stubbing
- Auto-waiting

**Cons vs Playwright:**
- Cypress doubles Node dependency count
- Runs separately from PHP tests — no shared helpers
- Smaller browser matrix (Chromium-only)
- Slower than Playwright for test execution

### D. Pest Feature Tests + Vitest Alpine Unit Tests

Pest for API tests, Vitest for Alpine JS module tests in isolation.

Vitest imports the Alpine JS modules (`book-editions.js`, `shelf-form.js`) directly, mocks Axios, and tests method behavior. No browser needed.

**Pros:**
- Fastest option (sub-ms per test)
- Tests JS logic directly
- Integrates with Vite build pipeline
- No browser infrastructure

**Cons:**
- Doesn't test real DOM interaction (`x-model`, `x-show`, event handling)
- Doesn't test Blade template rendering
- Mocks must match real API behavior — drift possible
- Can't catch CSS/layout regressions

### E. Pest Feature Tests Only

Only API tests, no browser/JS testing.

**Pros:**
- Lowest effort
- Catches server-side bugs

**Cons:**
- Catches 0 of the class of bugs that involve JS/DOM interaction (the shelve `===` bug)
- No safety net for Alpine migration bugs
- Manual testing still required

## Decision Outcome

**Chosen: Option A — Pest Feature Tests + Playwright.**

The ADR originally chose Dusk, but before implementation the decision was revisited. Dusk's ChromeDriver maintenance burden and PHP-centric test syntax didn't fit a stack where all interactive UI is Alpine.js (JS-based). Playwright's auto-waiting, trace viewer, and JS-native assertion model proved more practical for testing Alpine AJAX interactions.

### Rationale

1. **The shelve bug proves we need browser-level testing.** A `===` vs `==` bug in Alpine JS is invisible to both Pest API tests and Vitest unit tests (mocked Axios wouldn't catch value type mismatches from real `<select>` elements).

2. **Playwright runs on host Node.js, not in Docker.** The app's dev server is already on `localhost:8081`. No Chrome/Chromedriver installation inside the container — one fewer moving part.

3. **Pest API tests provide fast feedback** for server-side changes (endpoint changes, model changes, routing). These run inside the Docker container in milliseconds. Playwright tests are reserved for full-stack scenarios.

4. **Playwright's auto-waiting is critical for Alpine.js.** Alpine's reactivity is asynchronous — `x-show` toggles, `x-model` updates, and AJAX responses all have timing gaps. Dusk would require explicit `waitFor()` calls; Playwright's auto-waiting resolves these implicitly.

5. **Playwright's trace viewer + screenshot on failure** provides better debugging than Dusk's console output. Failed tests show exactly what the browser saw at the moment of failure.

6. **Vitest is rejected (for now) because** it doesn't provide enough value over Playwright for the Alpine migration phase. Post-migration, when the stack is stable, Vitest might be worth adding for unit-testing Alpine components in isolation.

7. **Cypress is rejected because** Playwright is faster, lighter, and supports more browsers for the same testing patterns.

### Consequences

**Positive:**
- Server-side bugs caught at Pest speed (~50ms)
- Browser-level bugs caught by Playwright before manual testing
- Playwright's auto-waiting reduces flaky test issues common with Alpine
- Trace viewer + screenshots for debugging failures
- JS-native test syntax matches the Alpine JS stack
- No Chrome/ChromeDriver in Docker

**Negative:**
- Two test environments: Pest in Docker, Playwright on host
- Playwright tests require running dev server (not isolated like Dusk's environment)
- No shared `RefreshDatabase` between layers — Playwright creates data through UI
- Database state persists between test runs (must create data via UI)

**Neutral:**
- Playwright uses standard JS (Node.js) — no new language to learn
- Tests are organized per Alpine component (corresponding to specs)

## Compliance

1. All API endpoint changes must have corresponding Pest feature tests covering success and failure cases.
2. All Alpine components must have at least one Playwright browser test covering the primary interaction flow (add/edit/delete).
3. Playwright tests must register a fresh user per test file and use serial mode for intra-file tests.
4. CI (when set up) must run Pest tests on every push. Playwright tests run on a schedule or before release.
5. `php artisan view:clear` must be run after any view/cache change to prevent stale Ziggy namespace errors.
