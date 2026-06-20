# RedirectIfAuthenticated Multi-Guard Middleware

## Status

Superseded by ADR-0013

## Context and Problem Statement

Laravel 8+ includes a default `RedirectIfAuthenticated` middleware, but the original version only handled a single guard. The application's `RedirectIfAuthenticated` was extended to accept variable guards via `...$guards` syntax, iterating over each guard to check authentication.

The app defines two guards (`web` and `api`) in `config/auth.php`. The custom middleware was preserved across upgrades on the assumption it handles multi-guard scenarios.

However, every `guest` middleware call in the codebase is bare — no guard parameter is passed — so the default (`web`) is always used. The variadic guard support is never exercised. The framework default would work identically.

## Decision Drivers

* Must keep middleware working with Laravel 8+ middleware signature
* Must handle multiple guards gracefully
* Must use the `HOME` constant from `RouteServiceProvider`

## Considered Options

* **Use default framework middleware** — would lose multi-guard support
* **Keep custom middleware** — preserve existing, tested behavior

## Decision Outcome

Chosen option: "Keep custom middleware". The existing implementation with `...$guards` and `Auth::guard($guard)->check()` was preserved. Redirects to `/home` when any guard is authenticated.

### Consequences

* Good, because multi-guard support is maintained
* Good, because the middleware signature matches Laravel 8+ expectations
* Bad, because it's custom code that must be maintained

## Confirmation

`php artisan route:middleware` lists `guest` → `App\Http\Middleware\RedirectIfAuthenticated`.
