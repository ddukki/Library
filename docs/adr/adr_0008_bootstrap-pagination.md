# Bootstrap Pagination Rendering for Legacy Frontend

## Status

Approved

## Context and Problem Statement

Laravel 8+ defaults to Tailwind CSS for pagination views. The legacy frontend (still used during the backend migration) uses Bootstrap 4, which has different pagination HTML structure and CSS classes.

Without intervention, `$books->links()` in Blade templates renders Tailwind-styled pagination that appears unstyled or broken when mixed with Bootstrap 4.

## Decision Drivers

* The legacy frontend must remain functional during backend upgrades
* Pagination must render with Bootstrap-compatible HTML
* Must be a zero-touch change to Blade templates and Vue components

## Considered Options

* **`Paginator::useBootstrap()`**: call in `AppServiceProvider::boot()` to globally set Bootstrap pagination
* **Custom pagination views**: publish and modify Blade pagination templates
* **Accept Tailwind styling**: let the old frontend render unstyled pagination

## Decision Outcome

Chosen option: "`Paginator::useBootstrap()`", because it restores Bootstrap 4 pagination globally with a single line in `AppServiceProvider::boot()`. No template changes needed.

### Consequences

* Good, because pagination renders correctly with Bootstrap 4 classes
* Good, because it's a one-line change in the service provider
* Bad, because this call must be removed when the frontend is overhauled to Alpine.js
* Bad, because it only affects server-rendered pagination, not Vue components

### Revisit Trigger

Revisit after the Alpine.js frontend rewrite. The `Paginator::useBootstrap()` call in `AppServiceProvider` must be removed when the frontend no longer uses Bootstrap 4. Search for `useBootstrap` in `AppServiceProvider` during the frontend rewrite task.

### Confirmation

Blade templates using `$records->links()` render HTML with Bootstrap 4 pagination classes (`pagination`, `page-item`, `page-link`).
