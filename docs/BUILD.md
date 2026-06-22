# Build Instructions

## Prerequisites

- **Docker Desktop** (for PHP runtime — no PHP/Composer on host)
- **Node.js 20+** (for frontend)

## PHP Setup (Docker)

All PHP/Composer commands run inside Docker. No PHP or Composer installation needed on host.

```bash
# Install PHP dependencies
docker run --rm -v ${PWD}:/app -w /app composer:2.0 composer install

# Run artisan commands
docker run --rm -v ${PWD}:/var/www/html -w /var/www/html php:8.4-cli php artisan <command>

# Start dev server (SQLite, threaded workers for concurrent AJAX)
docker run --rm -d --name lib-dev -p 8080:80 `
  -v ${PWD}:/var/www/html `
  -w /var/www/html `
  -e PHP_CLI_SERVER_WORKERS=4 `
  php:8.4-cli php -S 0.0.0.0:80 -t /var/www/html/public
```

> **Note:** `PHP_CLI_SERVER_WORKERS=4` is required — the single-threaded built-in server causes multi-second delays when the app makes concurrent Vue AJAX requests.

## Frontend Build

### Current (Laravel Mix v4 + Vue 2 + jQuery + Bootstrap 4)

```bash
npm install

# Production build (Node 22+ requires legacy OpenSSL provider)
$env:NODE_OPTIONS="--openssl-legacy-provider"
npm run production
```

Mix v4 is incompatible with Node 22's default OpenSSL settings. The `--openssl-legacy-provider` flag is required until we upgrade to Vite.

### Future (Vite + Alpine.js + SCSS)

Planned for Step 7 of the upgrade. Will replace the above entirely.

## Environment

Copy `.env.example` to `.env` and configure:

```bash
cp .env.example .env
```

The app uses **SQLite** — no MySQL server needed. Ensure `DB_DATABASE` in `.env` is an absolute path to survive Docker working-directory changes:

```
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

## Testing

```bash
# Run PHP tests
docker run --rm -v ${PWD}:/var/www/html -w /var/www/html php:8.4-cli `
  php vendor/bin/phpunit
```

## Quick Start (full flow)

```bash
# 1. Start Docker Desktop

# 2. Install PHP deps
docker run --rm -v ${PWD}:/app -w /app composer:latest composer install

# 3. Install & build frontend
npm install
$env:NODE_OPTIONS="--openssl-legacy-provider"
npm run production

# 4. Set up .env
cp .env.example .env
# Edit DB_DATABASE to absolute path as above

# 5. Run migrations
docker run --rm -v ${PWD}:/var/www/html -w /var/www/html php:8.4-cli php artisan migrate

# 6. Start server
docker run --rm -d --name lib-dev -p 8080:80 `
  -v ${PWD}:/var/www/html `
  -w /var/www/html `
  -e PHP_CLI_SERVER_WORKERS=4 `
  php:8.4-cli php -S 0.0.0.0:80 -t /var/www/html/public

# Visit http://localhost:8080
```

## Version Matrix

| Laravel | Docker Image    | PHP   | Notes                    |
|---------|-----------------|-------|--------------------------|
| 8.x     | `composer:2.0`  | 8.0   | Completed                |
| 9.x     | `composer:2.2`  | 8.0+  | Completed                |
| 10.x    | `composer:2.4`  | 8.1+  | Completed                |
| 11.x    | `composer:2.7`  | 8.2+  | Completed                |
| 12.x    | `composer:latest`  | 8.2+  | Completed                |
| 13.x    | `composer:latest`| 8.4+ | Current step             |
