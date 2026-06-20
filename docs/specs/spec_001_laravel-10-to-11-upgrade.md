# Step 04: Laravel 10 → 11 Upgrade

## References

- ADR-0001 (Incremental Upgrade as Practice Vehicle)
- ADR-0002 (Docker Development Environment)
- ADR-0003 (SQLite Database as Migration Stopgap)
- ADR-0005 (Retain Legacy Frontend During Backend Migration)
- ADR-0006 (PHP Version Pinning)
- ADR-0007 (Threaded PHP Dev Server)
- ADR-0008 (Bootstrap Pagination for Legacy Frontend)
- ADR-0013 (Revert to Framework Default Guest Middleware)
- ADR-0014 (Revert to Framework Default TrustProxies)
- ADR-0011 (Retain Laravel Stack)
- ADR-0012 (ADR/Spec Separation)

## Upgrade Map

| From | To |
|------|----|
| Laravel 10.50.2 | Laravel 11.x |
| PHP ^8.1 / platform 8.1 | PHP ^8.2 / platform 8.2 |
| Docker php:8.1-cli | Docker php:8.2-cli |
| Docker composer:2.4 | Docker composer:2.7 |
| PHPUnit ^9.6 | PHPUnit ^10.5 |
| Collision ^6.4 | Collision ^8.0 |
| app/Http/Kernel.php | → bootstrap/app.php |
| app/Exceptions/Handler.php | → bootstrap/app.php |
| app/Console/Kernel.php | → routes/console.php |

## Behavior Contract

```
Feature: Laravel 10 → 11 Upgrade
  As a developer performing incremental upgrades
  I want the application to work identically under Laravel 11
  So that users experience no regressions

  Background:
    Given the application is running in a Docker container with php:8.2-cli
    And dependencies are installed via composer:2.7
    And the database is SQLite at /var/www/html/database/database.sqlite

  Scenario: PHP Version Constraint
    Given composer.json exists
    When the platform config is inspected
    Then the platform.php must be "8.2"
    And the php requirement must be "^8.2"

  Scenario: PHPUnit Execution
    Given vendor/bin/phpunit exists
    When php vendor/bin/phpunit is run
    Then the exit code must be 0
    And no E_DEPRECATED errors must be emitted

  Scenario: Artisan Commands
    Given the Docker container is running
    When php artisan list is executed
    Then the exit code must be 0
    And common commands (migrate, route:list, config:cache) must work

  Scenario: Guest Routes
    Given I am not authenticated
    When I visit /login
    Then I should see the login form
    When I visit /register
    Then I should see the registration form
    When I visit /home
    Then I should be redirected to /login

  Scenario: Authenticated Routes
    Given I am authenticated as a valid user
    When I visit /home
    Then I should see the dashboard
    When I visit /books
    Then I should see the book listing
    When I visit /authors
    Then I should see the author listing
    When I visit /shelves
    Then I should see the shelf listing

  Scenario: Book CRUD
    Given I am authenticated
    When I create a book with title, author, and ISBN
    Then the book should appear in the listing
    When I edit the book title
    Then the change should persist on reload
    When I delete the book
    Then it should no longer appear in the listing

  Scenario: Author CRUD
    Given I am authenticated
    When I create an author with name and biography
    Then the author should appear in the listing
    When I edit the author
    Then the change should persist on reload
    When I delete the author
    Then it should no longer appear in the listing

  Scenario: Search Books
    Given there are books in the database
    When I search for a book by title
    Then matching books should appear in results
    When I search for a book by author name
    Then matching books should appear in results

  Scenario: Bootstrap 4 Pagination
    Given there are many books
    When I view the book listing page
    Then pagination controls must use Bootstrap 4 classes
    And page navigation must work

  Scenario: Legacy Frontend Build
    Given the frontend has been built with Mix
    When I load any page
    Then CSS and JS assets must load without 404s
    And Vue 2 components must mount

  Scenario: Threaded Dev Server
    Given the dev server is started with PHP_CLI_SERVER_WORKERS=4
    When I make concurrent AJAX requests
    Then responses must arrive without multi-second delays

  Scenario: RedirectIfAuthenticated Middleware (ADR-0013)
    Given the custom RedirectIfAuthenticated class has been removed
    When I inspect registered middleware
    Then guest must resolve to Illuminate\Routing\Middleware\RedirectIfAuthenticated
    And guest route behavior must be unchanged

  Scenario: TrustProxies Middleware (ADR-0014)
    Given the custom TrustProxies class has been removed
    When I inspect registered middleware
    Then the global middleware stack must use Illuminate\Http\Middleware\TrustProxies instead of App\Http\Middleware\TrustProxies
    And request handling must be unchanged
```

## Upgrade Procedure

### Step A: Update Docker Build

1. Update `docs/BUILD.md` version matrix
2. Verify php:8.2-cli and composer:2.7 are available

### Step B: Update composer.json

1. Change `"php": "^8.1"` → `"^8.2"`
2. Change `platform.php` → `"8.2"`
3. Change `"laravel/framework": "^10.0"` → `"^11.0"`
4. Change `"phpunit/phpunit": "^9.6"` → `"^10.5"`
5. Change `"nunomaduro/collision": "^6.4"` → `"^8.0"`
6. Run `composer update` inside Docker

### Step C: Bootstrap Restructure (L11)

1. Replace `bootstrap/app.php` with L11-style using `return Application::configure(...)`
2. Move middleware from `app/Http/Kernel.php` into `bootstrap/app.php`:
   - Global middleware
   - Web middleware group
   - API middleware group
   - Route middleware aliases (removing dead custom classes)
   - Middleware priority (L11 removes this property — verify)

3. Remove `app/Http/Kernel.php`
4. Ensure `routes/console.php` exists (already does)
5. Remove `app/Console/Kernel.php`
6. Remove `app/Exceptions/Handler.php`

### Step D: Remove Dead Code

1. Remove `app/Http/Middleware/RedirectIfAuthenticated.php`
2. Remove `app/Http/Middleware/TrustProxies.php`
3. Update middleware references in bootstrap/app.php

### Step E: Config Cleanup

If `php artisan config:publish` reveals missing config files that the app depends on, publish them. Laravel 11 no longer ships `broadcasting.php`, `cors.php`, `hashing.php`, `sanctum.php`, `view.php` by default.

### Step F: Test

1. `php vendor/bin/phpunit` — full test suite
2. Smoke-test all routes manually (guest + authenticated)
3. Verify frontend assets load

## Regression Risk

- `laravel/ui` may not support L11 natively — verify after upgrade. If incompatible, this may force the frontend rewrite earlier than planned (contingency).
- `tightenco/ziggy` v1.x compatibility with L11 — may need to upgrade to ziggy v2.x.
- Middleware priority removal in L11 — currently only 6 entries with standard middleware; verify framework handles this correctly.
