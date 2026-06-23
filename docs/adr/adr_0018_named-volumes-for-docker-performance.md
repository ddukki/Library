# ADR-0018: Named Docker Volumes for Bind-Mount Performance

**Status:** Approved
**Date:** 2026-06-22
**Replaces:** None
**Supersedes:** None
**Revisit Trigger:** When/if development moves to a native Linux environment or the Docker Desktop filesystem bridge is no longer a bottleneck, evaluate reverting to simpler bind-mount-only setup.

## Context

The app runs PHP/Laravel inside a Docker container on Windows via Docker Desktop. The project directory is bind-mounted (`-v ${PWD}:/var/www/html`) so code changes on the host are instantly visible inside the container.

On Linux, bind mounts are native kernel operations — the container reads the host filesystem directly. On Windows (and macOS), Docker Desktop runs Linux inside a VM. Bind mounts cross a virtualized filesystem bridge (currently gRPC FUSE) that adds latency to every file operation.

Laravel's boot process is file-intensive:
- Composer autoload scans vendor directories (thousands of files) on every request
- Config loading reads dozens of files from `config/`
- Service provider registration reads files from `vendor/` and `app/`
- Route and view loading read additional files

Measured boot phases on the bind mount:

| Phase | Time |
|-------|------|
| Composer autoload | 548ms |
| App bootstrap | 348ms |
| Kernel boot | 556ms |
| **Total cold boot** | **~1.45s** |

This made page loads take 1.5–2s and the test suite 8.6s — unacceptable for development iteration.

## Decision Drivers

- Page loads must be fast enough for comfortable development (<500ms cold, <100ms warm)
- Code changes on the host must reflect immediately in the container (no manual sync step)
- Must not require additional Dockerfiles or image rebuilds for every change
- Must work with the existing Docker setup (no docker-compose migration required)
- The `vendor/` directory is read-heavy and changes only on `composer install` — ideal for a volume
- The SQLite database is write-heavy (fsync on every transaction) — also benefits from a volume

## Considered Options

- **Bind mount only** — current state. Simple but slow. ~1.5s cold boot.
- **Docker named volumes for dependency directories** — move `vendor/` (and optionally `database/`) to Docker named volumes. Volumes live in the Docker VM's ext4 filesystem, avoiding the cross-OS bridge.
- **WSL2 native filesystem** — store the project inside WSL2's ext4 (`~/project`) and use Docker's WSL2 integration. Eliminates the bind-mount bridge entirely. Requires using WSL2 shell for all operations.
- **Rsync-based file sync** — use a sidecar container that rsyncs changes from the bind mount to a volume periodically. Adds complexity and lag.
- **PHP-FPM + Nginx** — switch to a production-grade web server. Fixes opcache behavior and preloading but adds configuration overhead. Doesn't directly address filesystem I/O.
- **Docker Sync / Mutagen** — third-party file synchronization tools. Additional dependency and setup complexity.

## Decision Outcome

Chosen option: **Docker named volumes for slow I/O paths (`vendor/` + SQLite DB)**, because it provides the largest performance gain with minimal configuration change.

Two named volumes created:
- `lib-vendor` — mounted at `/var/www/html/vendor`
- `lib-db` — mounted at `/var/lib/library/db` (SQLite database)

Each is a one-time setup (`docker volume create`) and requires no ongoing maintenance. Post-`composer install`, the vendor files must be copied into the volume (see Confirmation).

### Consequences

- Good, because cold page load dropped from 2.08s to 0.30s (7x)
- Good, because warm page load dropped from 1.58s to 0.07s (23x)
- Good, because test suite dropped from 8.6s to 1.7s (5x)
- Good, because the SQLite database no longer goes through the slow bind-mount bridge for transaction fsync calls
- Good, because no additional Dockerfiles, images, or third-party tools are required
- Bad, because `vendor/` must be re-copied into the volume after every `composer install`/update
- Bad, because the Docker run command is longer with additional `-v` flags
- Bad, because the volume contents are opaque (not directly visible on the host filesystem)

### Revisit Trigger

If development moves to a native Linux environment (where bind mounts are free), revert to simple bind-mount-only setup. Similarly if Docker Desktop's cross-OS filesystem performance improves to within ~20% of native.

### Confirmation

Performance measured with `curl -w "%{time_total}s"` on a cold container start:

| Metric | Bind mount | Named volumes | Improvement |
|--------|-----------|---------------|-------------|
| Homepage (cold) | 2.08s | 0.30s | 7x |
| Homepage (warm) | 1.58s | 0.07s | 23x |
| Shelves API | 1.72s | 0.09s | 19x |
| PHPUnit suite | 8.6s | 1.7s | 5x |

Vendor files copied into volume:
```bash
docker run --rm -v lib-vendor:/data -v ${PWD}:/src alpine cp -a /src/vendor/. /data/
```

## Pros and Cons of the Options

### Bind mount only (status quo)

- Good, because simple — one `-v` flag, zero setup
- Bad, because 1.5–2s cold boot makes development painful on Windows/macOS

### Named volumes for dependency directories

- Good, because 7–23x improvement on page loads
- Good, because no new dependencies or Dockerfiles
- Good, because volumes persist across container restarts (survive `--rm`)
- Bad, because vendor must be re-copied after composer operations
- Bad, because volume contents are not directly browsable on the host

### WSL2 native filesystem

- Good, because eliminates the bind-mount bridge entirely — best performance
- Bad, because requires using WSL2 shell, not PowerShell/CMD
- Bad, because editor integration (VS Code WSL remote) adds another layer
- Bad, because path conventions differ from Windows

### Rsync-based sync

- Good, because keeps files in native Docker VM storage
- Bad, because adds a polling/sync loop with latency
- Bad, because introduces another container and complexity

### PHP-FPM + Nginx

- Good, because enables opcache preloading and proper worker management
- Good, because production-like setup
- Bad, because does not fix the filesystem I/O bottleneck directly
- Bad, because adds significant configuration overhead

### Docker Sync / Mutagen

- Good, because purpose-built for this problem
- Bad, because adds a third-party dependency with its own quirks
- Bad, because additional setup and CI burden
