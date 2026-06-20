# SQLite as Development Database

## Status

Approved

## Context and Problem Statement

The original application was built for MySQL. Running MySQL locally requires a database server, credentials management, and creates state that must be migrated between versions. For a framework upgrade project where the schema is stable and the focus is on framework mechanics, server-based databases add operational overhead without benefit. This is a temporary trade-off for the migration period — the app will eventually return to a server-based database after the Laravel 13 migration completes.

## Decision Drivers

* Zero-configuration database for development
* No database server setup or credential management
* Deterministic, file-based state that can be versioned or reset trivially
* Must work identically under Docker

## Considered Options

* **SQLite** — file-based, zero-config, PHP has built-in PDO driver
* **MySQL via Docker** — spinning up a `mysql:8` container alongside the app
* **MySQL on host** — requires installing and configuring MySQL server

## Decision Outcome

Chosen option: "SQLite as a migration-phase stopgap", because Laravel's query builder and Eloquent ORM abstract nearly all database differences, and SQLite eliminates server management entirely during the upgrade sprint. The database will be migrated to a server-based solution after the Laravel 13 migration completes.

### Consequences

* Good, because no database server setup is required during the upgrade sprint
* Good, because the database is a single file that can be reset with `rm database.sqlite && touch database.sqlite && php artisan migrate`
* Good, because it works identically on Windows, macOS, Linux, and inside Docker
* Bad, because MySQL-specific features (full-text indexes, `SET` columns, etc.) would not be usable — but these are not required by the current schema
* Bad, because concurrent write performance is worse than MySQL — irrelevant for single-user dev
* Bad, because we must eventually migrate back to a server-based database post-migration, potentially surfacing SQL compatibility issues deferred during this phase

### Revisit Trigger

Revisit after Laravel 13 backend migration completes. Evaluate whether to migrate back to MySQL (or another server-based database) for production. The SQLite phase defers SQL compatibility issues — the longer it persists, the harder the eventual migration.

### Confirmation

The `.env` file sets `DB_CONNECTION=sqlite` and `DB_DATABASE` to the absolute path of the SQLite file. Running `php artisan migrate --force` creates the schema successfully. All CRUD endpoints operate correctly.

## Pros and Cons of the Options

### SQLite

* Good, because zero configuration
* Good, because file is portable and resettable
* Bad, because it does not catch MySQL-specific SQL incompatibilities

### MySQL via Docker

* Good, because it matches the production database
* Bad, because it requires Docker networking and volume configuration
* Bad, because state persists between runs unless volumes are destroyed
