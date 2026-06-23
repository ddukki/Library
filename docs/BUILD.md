# Build Instructions

## Prerequisites

- **Docker Desktop** (for PHP runtime — no PHP/Composer on host)
- **Node.js 20+** (for frontend)

## PHP Setup (Docker)

All PHP/Composer commands run inside Docker. No PHP or Composer installation needed on host.

```bash
# Install PHP dependencies (vendor/ should be on a Docker volume for speed)
docker run --rm -v lib-vendor:/app/vendor -v ${PWD}:/app -w /app composer:latest composer install

# Run artisan commands (needs DB + vendor volumes for slow filesystems)
docker run --rm -v lib-db:/var/lib/library/db -v lib-vendor:/var/www/html/vendor -v ${PWD}:/var/www/html -w /var/www/html php:8.4-cli php artisan <command>

# Start dev server (threaded workers for concurrent AJAX)
docker run --rm -d --name lib-dev -p 8081:80 `
  -v lib-db:/var/lib/library/db `
  -v lib-vendor:/var/www/html/vendor `
  -v ${PWD}:/var/www/html `
  -w /var/www/html `
  -e PHP_CLI_SERVER_WORKERS=4 `
  php:8.4-cli php -S 0.0.0.0:80 -t /var/www/html/public
```

> **Performance:** Docker Desktop bind mounts are slow on Windows/macOS. Named volumes (`lib-db`, `lib-vendor`) bypass this. Create them with `docker volume create lib-db && docker volume create lib-vendor` before first start. The vendor volume takes precedence over the bind mount at `/var/www/html/vendor`.

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

The app uses **SQLite** — no MySQL server needed. The database file lives on a Docker named volume to avoid slow bind-mount I/O:

```
DB_CONNECTION=sqlite
DB_DATABASE=/var/lib/library/db/database.sqlite
```

## Testing

```bash
# Run PHP tests
docker run --rm -v lib-vendor:/var/www/html/vendor -v ${PWD}:/var/www/html -w /var/www/html php:8.4-cli `
  php vendor/bin/phpunit
```

## Quick Start (full flow)

```bash
# 1. Start Docker Desktop

# 2. Create volumes (one-time)
docker volume create lib-db
docker volume create lib-vendor

# 3. Install PHP deps (mounts vendor on named volume for speed)
docker run --rm -v lib-vendor:/app/vendor -v ${PWD}:/app -w /app composer:latest composer install

# 4. Install & build frontend
npm install
npm run build

# 5. Set up .env
cp .env.example .env
# Edit DB_DATABASE to: /var/lib/library/db/database.sqlite

# 6. Run migrations (creates schema in volume)
docker run --rm -v lib-db:/var/lib/library/db -v lib-vendor:/var/www/html/vendor -v ${PWD}:/var/www/html -w /var/www/html php:8.4-cli php artisan migrate

# 7. Start server
docker run --rm -d --name lib-dev -p 8081:80 `
  -v lib-db:/var/lib/library/db `
  -v lib-vendor:/var/www/html/vendor `
  -v ${PWD}:/var/www/html `
  -w /var/www/html `
  -e PHP_CLI_SERVER_WORKERS=4 `
  php:8.4-cli php -S 0.0.0.0:80 -t /var/www/html/public

# Visit http://localhost:8081
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
