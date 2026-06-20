# Revert to Framework Default TrustProxies Middleware

## Status

Approved

## Context and Problem Statement

ADR-0010 documented a custom `App\Http\Middleware\TrustProxies` subclass that extended the framework base with an explicit `$headers` bitmask including `X-Forwarded-AWS-ELB`. This was carried over from the original production deployment behind an AWS Elastic Load Balancer.

The framework base class (`Illuminate\Http\Middleware\TrustProxies`) defaults to the same set of headers minus `X-Forwarded-AWS-ELB`. In the current Docker development environment (no proxies, no load balancers), the custom subclass has no effect. The AWS ELB header is only meaningful when deployed behind an ELB, which is not planned during this migration sprint.

## Decision Drivers

* Eliminate custom code that provides no benefit in the current environment
* Reduce maintenance surface during the upgrade sprint
* Must not change behavior in the current (no-proxy) deployment

## Considered Options

* **Revert to framework default** — remove the custom middleware class, let the framework's built-in `TrustProxies` handle proxy configuration
* **Keep custom subclass** — the ADR-0010 decision, now known to be unnecessary for the migration phase

## Decision Outcome

Chosen option: "Revert to framework default". Remove `App\Http\Middleware\TrustProxies` and use `Illuminate\Http\Middleware\TrustProxies` from the global middleware stack.

If production deployment with AWS ELB is revisited post-migration, the custom subclass can be re-added at that point with a new ADR.

### Consequences

* Good, because one less custom class to maintain during upgrades
* Good, because behavior is unchanged in the Docker dev environment
* Bad, because if deployment behind AWS ELB is needed, the `HEADER_X_FORWARDED_AWS_ELB` flag must be re-added via config or custom middleware

### Confirmation

* `App\Http\Middleware\TrustProxies` is removed
* `App\Http\Kernel` registers `Illuminate\Http\Middleware\TrustProxies::class`
* `php artisan route:list` loads without error
