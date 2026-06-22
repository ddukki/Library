# PHP Version Pinning via Composer Platform Config

## Status

Approved

## Context and Problem Statement

Composer resolves package versions based on the PHP version returned by the host. When running inside a `composer:2.4` Docker image (PHP 8.3.10), Composer may select packages that require PHP 8.2+ features. However, the application runs in a separate `php:8.1-cli` container.

This mismatch causes `composer install` to succeed (resolving against PHP 8.3) but runtime failures occur when packages use 8.2/8.3 features in the production container.

## Decision Drivers

* Dependency resolution must match the runtime PHP version
* Pin must be simple and not require custom Dockerfiles
* Must work with the rolling `composer:2.x` tags

## Considered Options

* **Platform config**: set `"platform": { "php": "8.1" }` in `composer.json`
* **Custom Dockerfile**: build a custom image matching runtime PHP
* **Accept mismatch**: let Composer resolve against the builder's PHP

## Decision Outcome

Chosen option: "Platform config", because it's a single-line change in `composer.json`, works with any Composer image, and prevents Composer from selecting packages requiring newer PHP versions.

### Consequences

* Good, because dependency resolution accurately reflects runtime constraints
* Good, because it works with any Composer Docker image without custom builds
* Bad, because the platform config must be manually updated when the runtime PHP version changes
* Bad, because it only affects Composer resolution — runtime checks still happen at execution

### Confirmation

`composer.json` contains `"config": { "platform": { "php": "8.1" } }`. Running `composer install` with this config produces a `composer.lock` where no package requires PHP > 8.1.
