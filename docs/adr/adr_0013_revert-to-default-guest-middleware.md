# Revert to Framework Default Guest Middleware

## Status

Approved

## Context and Problem Statement

ADR-0009 documented a custom `App\Http\Middleware\RedirectIfAuthenticated` that accepted variadic guards (`...$guards`), on the assumption that the app needed multi-guard support for guest redirects.

The app defines two guards (`web` and `api`) in `config/auth.php`. However, every `guest` middleware call in the codebase is bare — no guard parameter is passed — so the default (`web`) is always used. The variadic guard support is never exercised.

The framework default middleware in Laravel 8+ uses a single optional guard parameter: `handle($request, Closure $next, $guard = null)`. This handles the actual usage pattern (bare `guest()`) identically while being zero-maintenance code.

## Decision Drivers

* Eliminate custom code that provides no benefit over the framework default
* Reduce maintenance surface during the upgrade sprint
* Must not change actual auth behavior

## Considered Options

* **Revert to framework default** — remove the custom middleware class, let Laravel's built-in `RedirectIfAuthenticated` handle guest redirects
* **Keep custom middleware** — the ADR-0009 decision, now known to be unnecessary

## Decision Outcome

Chosen option: "Revert to framework default". Remove `App\Http\Middleware\RedirectIfAuthenticated` and register the framework's `Illuminate\Routing\Middleware\RedirectIfAuthenticated` in `$routeMiddleware`.

### Consequences

* Good, because one less custom class to maintain during upgrades
* Good, because auth behavior is unchanged — all guest routes use the default `web` guard
* Bad, because if a future feature introduces a second guard used with `guest`, the variadic pattern would need to be re-added

### Confirmation

* `php artisan route:middleware` lists `guest` → `Illuminate\Routing\Middleware\RedirectIfAuthenticated`
* All guest routes (login, register, forgot password, reset password) redirect authenticated users to `/home`
