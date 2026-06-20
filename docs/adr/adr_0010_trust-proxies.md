# TrustProxies Middleware Configuration

## Status

Superseded by ADR-0014

## Context and Problem Statement

Laravel 8+ moved `TrustProxies` into `Illuminate\Http\Middleware\TrustProxies` and removed the need for a custom middleware subclass in many cases. The original application used a custom `App\Http\Middleware\TrustProxies` that extended the base middleware with explicit proxy header configuration.

The `$headers` property was configured to trust `X-Forwarded-For`, `X-Forwarded-Host`, `X-Forwarded-Port`, `X-Forwarded-Proto`, and `X-Forwarded-AWS-ELB` headers.

The framework base class defaults to the same set minus `X-Forwarded-AWS-ELB`. The AWS ELB header is only relevant behind an AWS Elastic Load Balancer — the current Docker dev environment has no proxies. The custom middleware is production baggage carried over to a development context where it has no effect.

## Decision Drivers

* Trusted proxy configuration is needed for deployment behind load balancers
* The middleware must be compatible with the Laravel 8+ class hierarchy

## Considered Options

* **Keep custom subclass** — preserve existing header configuration
* **Use base middleware with config** — configure in bootstrap/app.php

## Decision Outcome

Chosen option: "Keep custom subclass". The `App\Http\Middleware\TrustProxies` extends the framework base class and preserves the specific header configuration. This is backward compatible with Laravel 8+.

### Consequences

* Good, because proxy header trust is explicitly configured
* Good, because the class structure matches L8+ framework expectations

## Confirmation

`App\Http\Middleware\TrustProxies` is registered in the global middleware stack.
