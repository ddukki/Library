# Docker-Isolated PHP Development Environment

## Status

Approved

## Context and Problem Statement

The host machine (Windows) has no PHP runtime, and installing and switching between multiple PHP versions (8.0, 8.1, 8.2, 8.3) is error-prone and pollutes the system. Composer also needs the correct PHP version for dependency resolution.

Each Laravel upgrade step requires a different PHP minor version, and the final target (Laravel 13.x) requires PHP 8.3+. We need a way to run Artisan commands, tests, and the dev server without installing PHP on the host.

## Decision Drivers

* Zero PHP/Composer installation on the host
* Ability to pin PHP version per upgrade step
* Consistent environment across team members
* Reproducible builds

## Considered Options

* **Docker `composer` image** for dependency resolution, `php:*-cli` for runtime
* **Laravel Sail** (built-in Docker wrapper)
* **Host PHP** via Chocolatey/Windows PHP installer
* **WSL2** with PHP installed inside

## Decision Outcome

Chosen option: "Docker `composer` image for dependency resolution + `php:*-cli` for runtime", because it gives explicit control over the PHP version for both Composer and the runtime, requires no host installation, and the image tags map directly to upgrade steps.

### Consequences

* Good, because Composer resolves dependencies against the exact PHP version the app will run on
* Good, because switching PHP versions is a one-line change to the Docker image tag
* Good, because no PHP artifacts pollute the host
* Bad, because file permissions between Docker and Windows can cause issues
* Bad, because Docker must be running for any PHP operation

### Confirmation

Each upgrade step runs `composer install` and `php artisan test` inside the target version's containers. The `docs/BUILD.md` documents the exact `docker run` commands.

## Pros and Cons of the Options

### Laravel Sail

* Good, because it's the official Laravel Docker wrapper
* Bad, because it bundles services (MySQL, Redis, etc.) we don't need for SQLite
* Bad, because it abstracts the PHP version behind environment variables
* Bad, because it adds startup overhead for what is essentially `php -S`

### Host PHP via Chocolatey

* Good, because it's simple for a single developer
* Bad, because switching versions requires uninstall/reinstall
* Bad, because it doesn't reproduce in CI
