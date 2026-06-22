# Comprehensive Spec Coverage

## Status

Draft

## Context and Problem Statement

The project has three upgrade-process specs (`spec_001`–`spec_003`) in `docs/specs/`, but no specs for the application's actual behavior — models, controllers, routes, validation, relationships, or user-facing contracts. This means:

- AI agents and future maintainers cannot verify behavior against a spec
- Bugs like `Book::shelves()` using the wrong relationship type or `Quote::edition()` having a lowercase class reference are discovered only by reading source code, not by spec violation
- There is no single authoritative description of "what the system should do"
- The SDD cycle (spec → code → test) applies only to upgrade steps, not to the application itself

Should we write specs for the entire existing codebase, write specs only for new/modified features, or leave the current spec-as-you-go approach?

## Decision Drivers

- AI agents need authoritative behavioral descriptions to work effectively
- Pre-existing bugs should be spec violations, not discoveries
- Writing specs for ~15 models + controllers is a significant up-front cost
- The frontend overhaul will change the user-facing contract, possibly invalidating UI specs
- SDD methodology (ADR-0012) requires specs for behavioral contracts
- The codebase is a learning/practice project, not production — perfection is not the goal

## Considered Options

### A. Comprehensive specs for all application behavior

Write specs for every model (relationships, scopes, validation, mass assignment), controller (route contracts, authorization, response shapes), and non-trivial behavior. Specs are living documents updated when behavior changes.

### B. Targeted specs: models only

Write specs only for Eloquent models — relationships, attributes, scopes, casts. Skip controllers and views (they change in the frontend overhaul).

### C. Spec-as-you-go (current approach)

Write specs only for new features or upgrades, as needed. Existing behavior has no spec coverage.

## Decision Outcome

**Chosen: B — Targeted specs: models only.**

Models are the most stable layer — they survive the frontend overhaul relatively unchanged. They're also where the known bugs live. Writing model specs gives agents a reliable reference for data contracts without investing effort in controller specs that will be rewritten for the new frontend.

### Consequences

* Good, because model specs document data contracts that survive the frontend overhaul.
* Good, because the three known bugs become spec violations (verifiable).
* Good, because models are the layer most relevant to AI agents working on the codebase.
* Good, because ~15 models is a bounded, completable scope (vs. open-ended "everything").
* Neutral, because controller/route specs are deferred until after the frontend rewrite stabilizes.
* Bad, because any spec rewrite costs more than spec-as-you-go (Option C).
* Bad, because model specs may miss behavioral contracts captured only in controllers (policies, authorization, response formatting).

### Revisit Trigger

After the frontend overhaul (Vue/Mix → Vite + Alpine), evaluate whether controllers need specs. If route logic was significantly restructured during the rewrite, write controller specs at that point.
