# Step 05: Laravel 11 → 12 Upgrade

## References

- ADR-0001 (Incremental Upgrade as Practice Vehicle)
- ADR-0002 (Docker Development Environment)
- ADR-0005 (Retain Legacy Frontend During Backend Migration)
- ADR-0006 (PHP Version Pinning)
- ADR-0007 (Threaded PHP Dev Server)
- ADR-0011 (Retain Laravel Stack)
- Spec 001 (Laravel 10 → 11 Upgrade)

## Upgrade Map

| From | To |
|------|----|
| Laravel 11.x | Laravel 12.x |
| PHP ^8.2 | PHP ^8.2 (unchanged) |
| Docker php:8.2-cli | Docker php:8.2-cli (unchanged) |
| Docker composer:2.7 | Docker composer:2.7 (unchanged) |
| PHPUnit ^10.5 | PHPUnit ^11.0 |
| Collision ^8.0 | Collision ^8.0 (unchanged) |
| bootstrap/app.php (L11) | bootstrap/app.php (L12 — min changes) |

## Behavior Contract

```gherkin
Feature: Laravel 11 → 12 Upgrade
  As a developer performing incremental upgrades
  I want the application to work identically under Laravel 12
  So that users experience no regressions

  Background:
    Given the application is running in a Docker container with php:8.2-cli
    And dependencies are installed via composer:2.7
    And the database is SQLite at /var/www/html/database/database.sqlite

  Scenario: Framework Version
    Given composer.json exists
    When I inspect the laravel/framework version
    Then it must be "^12.0"
    And php artisan --version must show "Laravel Framework 12"

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

  Scenario: Legacy Frontend
    Given the frontend is built with Laravel Mix
    When I load any page
    Then CSS and JS assets must load without 404s
```

## Upgrade Procedure

### Step A: Update composer.json

1. Change `"laravel/framework": "^11.0"` → `"^12.0"`
2. Change `"phpunit/phpunit": "^10.5"` → `"^11.0"`
3. Run `composer update` inside Docker

### Step B: Bootstrap Cleanup

1. Replace the Closure-based `guest` middleware alias with `Illuminate\Auth\Middleware\RedirectIfAuthenticated::class` (available in L12, not in `Illuminate\Routing\Middleware`)
2. Check for any removed/deprecated config keys

### Step C: Config Check

1. Run `php artisan config:publish --list` to identify missing configs
2. Publish any that the app depends on

### Step D: Test

1. `php vendor/bin/phpunit` — full test suite
2. Start dev server and smoke-test guest routes
3. Verify frontend assets load

## Regression Risk

- `laravel/ui` v4 compatibility with L12 — unknown. If broken, may force frontend rewrite.
- `tightenco/ziggy` v1.x with L12 — may need v2.x upgrade.
- PHPUnit 11 may flag additional deprecations from PHP 8.2.
