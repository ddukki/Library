# Step 06: Laravel 12 → 13 Upgrade

## References

- ADR-0001 (Incremental Upgrade as Practice Vehicle)
- ADR-0002 (Docker Development Environment)
- ADR-0005 (Retain Legacy Frontend During Backend Migration)
- ADR-0006 (PHP Version Pinning)
- ADR-0011 (Retain Laravel Stack)
- Spec 001 (Laravel 10 → 11 Upgrade)
- Spec 002 (Laravel 11 → 12 Upgrade)

## Upgrade Map

| From | To |
|------|----|
| Laravel 12.62.0 | Laravel 13.x |
| PHP ^8.2 | PHP ^8.4 (L13 requires 8.4+) |
| Platform config `php: 8.2` | Platform config `php: 8.4` |
| Docker php:8.2-cli | Docker php:8.4-cli |
| Docker composer:2.7 | Docker composer:latest (v2.10.1) |
| Tinker ^2.8 | Tinker ^3.0 (v2 doesn't support illuminate/support ^13) |
| PHPUnit ^11.0 | PHPUnit ^11.0 (unchanged — compatible with PHP 8.4) |
| Collision ^8.0 | Collision ^8.0 (unchanged) |

## Behavior Contract

```gherkin
Feature: Laravel 12 → 13 Upgrade
  As a developer performing incremental upgrades
  I want the application to work identically under Laravel 13
  So that users experience no regressions

  Background:
    Given the application is running in a Docker container with php:8.4-cli
    And dependencies are installed via composer:latest
    And the database is SQLite at /var/www/html/database/database.sqlite

  Scenario: PHP Version Constraint
    Given composer.json exists
    When the platform config is inspected
    Then the platform.php must be "8.4"
    And the php requirement must be "^8.4"

  Scenario: Framework Version
    Given vendor/laravel/framework is installed
    When I check the framework version
    Then it must be 13.x

  Scenario: PHPUnit Execution
    Given vendor/bin/phpunit exists
    When php vendor/bin/phpunit is run
    Then the exit code must be 0
    And no deprecation warnings must be emitted

  Scenario: Guest Routes
    Given I am not authenticated
    When I visit /login
    Then I should see the login form with HTTP 200
    When I visit /home
    Then I should be redirected to /login

  Scenario: Authenticated Routes
    Given I am authenticated as a valid user
    When I visit /home
    Then I should see the dashboard
    When I visit /books
    Then I should see the book listing
    When I create a book
    Then the book should persist

  Scenario: Legacy Frontend
    Given the frontend is built with Laravel Mix
    When I load any page
    Then CSS and JS assets must load without 404s
```

## Upgrade Procedure

### Step A: Update Docker

1. Pull `php:8.4-cli` image
2. Pull `composer:latest` image
3. Update `docs/BUILD.md` version matrix and image references

### Step B: Update composer.json

1. Change `"php": "^8.2"` → `"^8.4"`
2. Change `platform.php` → `"8.4"`
3. Change `"laravel/framework": "^12.0"` → `"^13.0"`
4. Run `composer update --with-all-dependencies` inside Docker
5. If `laravel/tinker` fails (doesn't support illuminate/support ^13), bump to `^3.0`

### Step C: Verify Bootstrap

L13 bootstrap structure matches L12. Verify `bootstrap/app.php` works without changes.
L13 provides default middleware aliases (auth, guest, signed, throttle, etc.) — custom aliases are
redundant. Remove unused imports and alias registrations. Keep `redirectGuestsTo('/login')`.
Check L13 reference skeleton for any new configuration requirements (e.g., `previous_keys` in config/app.php).

### Step D: Test

1. `php vendor/bin/phpunit` — full test suite
2. Start dev server and smoke-test guest routes
3. Verify frontend assets load
4. Test authenticated CRUD operations

## Regression Risk

- PHP 8.4 may flag new deprecations in app code or vendor packages
- `laravel/ui` v4 compatibility with L13 — unknown. May force early frontend rewrite.
- `tightenco/ziggy` v1.x with L13 — may need v2.x.
- This is the final backend step. After this, the frontend overhaul begins.
