# Threaded PHP Development Server for Concurrent Requests

## Status

Approved

## Context and Problem Statement

The Vue 2 frontend makes multiple simultaneous AJAX requests (e.g., loading authors, books, pagination data). PHP's built-in development server (`php -S`) is single-threaded — it processes one request at a time.

Under the single-threaded server, concurrent AJAX calls queue up sequentially. Each request takes 200–500ms, so 4–5 parallel requests take 2+ seconds to complete, making the UI feel sluggish.

## Decision Drivers

* AJAX-heavy frontend needs concurrent request handling
* Must work with the `php:8.1-cli` Docker image without installing a full web server
* Simple configuration, no Nginx/Apache setup

## Considered Options

* **`PHP_CLI_SERVER_WORKERS=4`**: PHP 8.1+ supports multi-worker built-in server
* **Nginx + PHP-FPM in Docker**: production-grade but complex to configure
* **Accept single-threaded**: degrade the user experience

## Decision Outcome

Chosen option: "`PHP_CLI_SERVER_WORKERS=4`", because PHP 8.1+ includes native multi-worker support for the built-in server. The environment variable is passed to the `docker run` command, requiring no additional configuration.

### Consequences

* Good, because parallel AJAX requests complete simultaneously
* Good, because it requires zero configuration — just an environment variable
* Good, because it uses the same `php -S` command the app was already using
* Bad, because it's only available in PHP 8.1+
* Bad, because it's not suitable for production
* Bad, because it's tied to the legacy Vue 2 frontend's parallel AJAX behavior — the Alpine.js rewrite may eliminate this constraint entirely, making the env var dead config. Revisit after frontend migration.

### Revisit Trigger

Revisit after the Alpine.js frontend rewrite. If the new frontend eliminates parallel AJAX requests or the project switches from `php -S` to a proper web server (Nginx/FrankenPHP), the `PHP_CLI_SERVER_WORKERS` env var becomes dead config and should be removed.

### Confirmation

The Docker run command includes `-e PHP_CLI_SERVER_WORKERS=4`. Loading `/library/authors` (which triggers multiple AJAX calls) renders in under 1 second instead of 3+.
