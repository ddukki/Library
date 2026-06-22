# Retain Laravel/PHP Stack Through Completion

## Status

Approved

## Context and Problem Statement

This project is a Laravel 5.8 codebase mid-migration to Laravel 13 via sequential major-version jumps (ADR-0001). The primary goal is learning SDD through real migration experience.

Is Laravel (and PHP) still the right choice in 2026, or should we switch frameworks mid-stream?

## Decision Drivers

- Knowledge is the primary output. The framework decision must serve that.
- If the ecosystem is structurally declining, switching costs are justified.
- The app is single-user CRUD on SQLite. Performance is not a constraint.

## Ecosystem State (June 2026)

**PHP** powers 70.8% of websites with a known server-side language (W3Techs June 2026). Next closest: Ruby at 6.9%. 61% of PHP sites run PHP 8.x. PHP 8.3–8.5 actively supported. PHP 9.0 being planned. Not declining in any meaningful sense — 70.8% of the web is not "dying."

**Laravel** leads PHP frameworks at 64% adoption (JetBrains 2025 survey). Laravel 13 shipped March 17, 2026 with zero breaking changes from 12, requires PHP 8.3+. Bug fixes until Q3 2027, security until Q1 2028. Regular monthly releases. Active ecosystem (Cloud, Boost AI, MCP).

**Performance:** Laravel benchmarks at ~300 req/s (Sharkbench, 2025). Go/Fiber is ~20x faster, Rust/Axum ~70x. For context: 300 req/s on a single-user library tracker means the server is idle >99% of the time. The bottleneck is SQLite disk I/O, not the framework. Arguments about throughput are cargo-culting for this workload — they matter at Facebook scale, not here.

## Considered Options

### A. Continue Laravel

Complete Steps 4–6 (10→11→12→13), then frontend rewrite. All work to date directly usable.

### B–E. Switch to AdonisJS, Go, Rust, or Django

All are ground-up rebuilds. None have a migration path from PHP/Laravel. Each requires learning an entirely new ecosystem orthogonal to the project's goal. Each abandons the git history of seven version-boundary crossings.

## Decision Outcome

**Chosen: A — Continue with Laravel through completion.**

PHP and Laravel are not declining. Laravel 13 is current, actively maintained, and supported through 2028. Choosing Laravel in 2026 is not a stranded-asset decision.

The stronger argument is project-specific:

1. **The codebase is the curriculum.** Switching frameworks mid-stream abandons the git history built so far (5.8→8→9→10) and replaces a migration exercise with a porting exercise. The developer learns AdonisJS or Go idioms, not how to upgrade a legacy Laravel app across seven framework generations.

2. **Every alternative is a ground-up rebuild**, which ADR-0001 explicitly rejected because it destroys migration history and teaches cargo-culting, not migration skill.

3. **Performance is irrelevant** — 300 req/s is ~700x the expected load. Arguments about Go/Rust throughput don't apply to a single-user SQLite CRUD app.

4. **Laravel's upgrade path is the best-documented in web frameworks.** Official guides, Laravel Shift, Rector, thousands of blog posts covering every version boundary. No alternative has this migration infrastructure.

5. **Future framework decisions** should be made post-completion with full knowledge of the app's actual requirements, not speculatively mid-stream.

### Consequences

- Good: zero sunk cost — all work to date remains usable.
- Good: learning objective preserved.
- Good: Laravel 13 support to 2028 provides years of runway.
- Bad: Laravel is the slowest popular framework — irrelevant at this workload.
- Bad: PHP's type system is weaker than TypeScript/Go/Rust — PHPStan/Psalm mitigate.
- Bad: PHP web share declined from 79% to 70% over 5 years — but 70% is still dominant, and absolute PHP site count grew with the web.

### Confirmation

- `php artisan test --testsuite=Feature` passes.
- App loads at `http://localhost:8080` (302 to login).
- `git log --oneline` continues showing the sequential upgrade chain.
