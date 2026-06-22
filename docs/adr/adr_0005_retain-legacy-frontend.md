# Graceful Upgrade: Retain Legacy Frontend During Backend Migration

## Status

Approved

## Context and Problem Statement

The frontend uses Vue 2, jQuery, Bootstrap 4, and Laravel Mix — all outdated and targeted for replacement. However, the backend upgrade from Laravel 5.8 to 13.x spans multiple major versions, each of which could introduce regressions.

Rewriting the frontend concurrently with the framework upgrade would double the surface area of change per step, making it harder to distinguish framework bugs from frontend bugs.

## Decision Drivers

* Each upgrade step should change exactly one layer
* The app must remain functional and demonstrable at every step
* The frontend overhaul is a large, independent task

## Considered Options

* **Frontend-first**: rewrite the frontend on the old Laravel 5.8 backend, then upgrade the backend
* **Backend-first**: upgrade the framework through all versions while the old frontend keeps working, then overhaul the frontend last
* **Parallel**: rewrite both simultaneously

## Decision Outcome

Chosen option: "Backend-first", because the old Vue 2 frontend communicates with Laravel through standard JSON API endpoints and Blade templates that remain stable across Laravel versions. This isolates framework upgrade issues to the backend layer.

### Consequences

* Good, because each upgrade step is validated by exercising the existing frontend
* Good, because the frontend rewrite is deferred as a single, focused task
* Bad, because the old frontend accumulates technical debt during the backend migration
* Bad, because temporary patch code (like Bootstrap pagination in AppServiceProvider) is needed to keep old UI working

### Revisit Trigger

Revisit after all backend upgrade steps (5.8→8→9→10→11→12→13) complete and the app is verified working on Laravel 13. The frontend rewrite (Vite + Alpine.js + SCSS) should be planned as a single spec-driven task with its own ADR cycle.

### Confirmation

At each backend upgrade step, the app is started and the main pages (/, /login, /library/authors) are verified to return 200, and the Vue components render without console errors.

## Pros and Cons of the Options

### Frontend-first

* Good, because the new frontend gets more testing time
* Bad, because the new frontend must handle both old and new API responses if the backend changes
* Bad, because effort is spent on a UI that will be re-tested after the backend migration
