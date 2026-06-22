# ADR-0017: Frontend Testing Strategy — PHPUnit API Tests + Laravel Dusk

**Status:** Approved
**Date:** 2026-06-22
**Replaces:** None
**Supersedes:** None
**Revisit Trigger:** When all Vue→Alpine migrations are complete and the stack is stable, evaluate whether Dusk complexity is justified vs lighter alternatives like Vitest + Playwright.

## Context

The app has Vue 2 components being incrementally migrated to Alpine.js (ADR-0016). Each migration involves Blade partials, Alpine JS modules, and AJAX interactions with Laravel API endpoints.

Currently, the only verification is manual — the developer clicks buttons and observes behavior. The shelve button bug (Spec 011) is a concrete case where:
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

### A. PHPUnit Feature Tests + Laravel Dusk

Two layers:
1. **PHPUnit HTTP tests** — call API endpoints (`POST library/editions/1/shelve/1`), verify JSON responses, status codes, and database state. Fast (~50ms per test), no browser needed.
2. **Laravel Dusk** — opens real Chrome, clicks buttons, fills forms, asserts DOM content. Tests the full stack from browser to database. Slower (~2-5s per test) but catches real interaction bugs.

Dusk is Laravel-native: uses the same test assertions as PHPUnit, supports `RefreshDatabase`, and runs inside the existing Docker container.

```
./vendor/bin/phpunit              # API tests (fast)
./vendor/bin/phpunit --testsuite=Feature  # API tests only
php artisan dusk                  # Browser tests (slow)
```

**Dusk ChromeDriver setup in Docker:**
- Install `php artisan dusk:chrome-driver` to download matching ChromeDriver
- Run headless Chrome in the container or on host
- Existing Docker setup may need Chrome binary installed

### B. PHPUnit Feature Tests + Cypress

PHPUnit for API tests, Cypress for browser E2E.

Cypress is a standalone JS framework: installs via npm, opens its own browser (Electron), uses JS assertions. Better developer experience (time-travel, auto-waiting, network stubbing) than Dusk.

**Pros over Dusk:**
- Better debugging (time-travel, screenshots, video)
- Network stubbing (test error states without backend)
- Auto-waiting (no explicit `wait()` calls)

**Cons vs Dusk:**
- Another Node dependency (already 37 packages, adding Cypress doubles count)
- Runs separately from PHP tests — no shared `RefreshDatabase` or Laravel test helpers
- Needs a running Laravel dev server (not the testing environment)
- Less integrated with existing toolchain

### C. PHPUnit Feature Tests + Vitest Alpine Unit Tests

PHPUnit for API tests, Vitest for Alpine JS module tests in isolation.

Vitest imports the Alpine JS modules (`book-editions.js`, `shelf-form.js`) directly, mocks Axios, and tests method behavior. No browser needed.

**Pros:**
- Fastest option (sub-ms per test)
- Tests JS logic directly (the `==` vs `===` bug would be caught)
- Integrates with Vite build pipeline
- No browser infrastructure

**Cons vs Dusk:**
- Doesn't test real DOM interaction (`x-model`, `x-show`, event handling)
- Doesn't test Blade template rendering
- Mocks must match real API behavior — drift possible
- Can't catch CSS/layout regressions

### D. PHPUnit Feature Tests Only

Only API tests, no browser/JS testing.

**Pros:**
- Lowest effort
- Catches server-side bugs

**Cons:**
- Catches 0 of the class of bugs that involve JS/DOM interaction (the shelve `===` bug)
- No safety net for Alpine migration bugs
- Manual testing still required

## Decision Outcome

**Chosen: Option A — PHPUnit Feature Tests + Laravel Dusk.**

### Rationale

1. **The shelve bug proves we need browser-level testing.** A `===` vs `==` bug in Alpine JS is invisible to both PHPUnit API tests and Vitest unit tests (mocked Axios wouldn't catch value type mismatches from real `<select>` elements).

2. **Dusk is native to Laravel.** It shares the same assertion syntax, database helpers, and test infrastructure. No separate test runner or dev server needed. This matters for a small team — less cognitive overhead.

3. **PHPUnit API tests provide fast feedback** for server-side changes (endpoint changes, model changes, routing). These run in milliseconds. Dusk tests are reserved for full-stack scenarios.

4. **Dusk + ChromeDriver can run inside Docker.** The existing container already has PHP + Composer. Adding Chrome and ChromeDriver is a one-time setup cost.

5. **Vitest is rejected (for now) because** it doesn't provide enough value over Dusk for the Alpine migration phase. Post-migration, when the stack is stable, Vitest might be worth adding for unit-testing Alpine components in isolation. Revisit trigger captures this.

6. **Cypress is rejected because** the standalone JS toolchain adds complexity without proportional benefit for a single-developer project. Dusk's tighter Laravel integration outweighs Cypress's DX advantages here.

### Consequences

**Positive:**
- Server-side bugs caught at PHPUnit speed (~50ms)
- Browser-level bugs caught by Dusk before manual testing
- Same assertion API for both layers (`$this->assertSee()`, `$this->assertJson()`)
- Incremental adoption — write tests for each new migration spec
- Dusk's `RefreshDatabase` trait keeps tests isolated

**Negative:**
- Dusk requires Chrome + ChromeDriver in the Docker container (one-time setup)
- Dusk tests are slower than unit tests
- ChromeDriver version must match Chrome version (maintenance burden)
- Two test commands instead of one

**Neutral:**
- Tests are PHP files alongside existing test suite — no new language/framework to learn
- Dusk browser tests can be run selectively via `--group` annotations during development
- Post-migration, evaluate whether heaviness is justified vs Vitest/Playwright

## Compliance

1. All new API endpoints and existing ones must have PHPUnit feature tests covering success and failure cases.
2. All Alpine components must have at least one Dusk browser test covering the primary interaction flow.
3. Dusk tests must use `RefreshDatabase` and seed minimal test data.
4. CI (when set up) must run PHPUnit tests on every push. Dusk tests run on a schedule or before release.
