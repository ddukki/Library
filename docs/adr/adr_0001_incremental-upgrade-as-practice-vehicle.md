# Incremental Upgrade as SDD Practice Vehicle

## Status

Approved

## Context and Problem Statement

This project exists to learn software design and development (SDD) methodology — how to write practical ADRs, create specs that survive contact with real code, and make architectural decisions with genuine trade-offs. We need a real, non-trivial codebase to practice on.

The practice vehicle is a Laravel 5.8 library tracker (books, authors, editions, shelves, quotes, reading progress) with Vue 2 + jQuery + Bootstrap 4 + Laravel Mix. Target is Laravel 13.x with Vite + Alpine.js + SCSS. The gap between 5.8 and 13 spans five major framework generations with real breaking changes, real deprecations, and real architectural decisions at every boundary.

A ground-up rebuild would reach the target fastest but destroys the practice material — no migration decisions to make, no breaking changes to resolve, no ADRs to write about real trade-offs. Every interesting decision would already be made by `laravel new`.

How do we structure the work so that each step produces a genuine SDD artifact — an ADR with real alternatives and trade-offs, or a spec tested against real code — rather than mechanical upgrade steps that could be automated?

## Artifact Lifecycle

Three layers with independent lifecycle rules:

1. **ADR** (immutable after approval) — documents a decision: context, options considered, trade-offs, rationale. If a gap is discovered, write a new ADR. Never edit an approved ADR.
2. **Spec** (living) — documents behavior: interfaces, edge cases, acceptance criteria. Refined as implementation reveals gaps. A failed build means the spec was imprecise.
3. **Code** — validates the spec. A passing build means the spec is correct for now.

Links are directional: Spec references ADR by number to surface constraints. Code references neither — it either passes or fails the spec.

## Decision Drivers

* SDD methodology is the primary output. The code is the evidence that the methodology works.
* Every ADR must document a decision with genuine alternatives and non-trivial trade-offs. No mechanical-change ADRs.
* Specs are the gate — a passing build validates the spec, not the ADR.
* The process must produce artifacts that are valid outside this project — transferable SDD skill.
* The codebase must remain functional throughout so SDD artifacts can be validated against a running system.

## Considered Options

* **Incremental version jumps with SDD artifacts** — upgrade through every major version in sequence (5.8→8→9→10→11→12→13). Each step produces specs and ADRs documenting the decisions encountered. Frontend rewrite is deferred to focus backend migration decisions.
* **Ground-up rebuild with SDD** — `laravel new` at 13.x, then design the target architecture through ADRs before writing any code. Produces clean ADRs but no practice resolving real breaking changes — the interesting decisions are already made by the framework.
* **Spec-first redesign** — ignore the existing codebase. Write complete specs for the library tracker from scratch, then build to spec. Purest SDD exercise but discards the existing codebase entirely — no practice evolving a real system.

## Decision Outcome

Chosen option: "Incremental version jumps with SDD artifacts". The sequence is fixed: 5.8 → 8 → 9 → 10 → 11 → 12 → 13. After the last backend step, the frontend is rewritten as a single spec-driven task.

Each upgrade step is scoped to a single Laravel version boundary. The diff at each step is the breaking-change resolution for that version. Steps that encounter an architectural decision produce an ADR. Steps that need precise behavior documentation produce a spec.

Mechanical compatibility fixes (route syntax changes, renamed helpers, config file diffs) go in commit messages, not ADRs or specs. Only decisions with genuine alternatives and trade-offs warrant an ADR. Only behavior that benefits from formal acceptance criteria warrants a spec.

### Consequences

* Good, because each version boundary surfaces genuine breaking-change decisions that require ADR-quality analysis.
* Good, because the three-layer model (ADR immutable, spec living, code as validator) is practiced at every step.
* Good, because failed builds are actionable — they reveal imprecision in a spec, not a wrong decision.
* Good, because discovering a missing ADR is not a failure — it's a signal to write one.
* Bad, because some upgrade steps have few or no architectural decisions — just mechanical changes. Those steps produce commits but not ADRs.
* Bad, because the exercise takes significantly longer than a rebuild, and some ADRs will document decisions that are obvious in retrospect.
* Bad, because deprecations and package conflicts compound across seven sequential upgrades, sometimes producing work that isn't architectural — just tedious.

### Confirmation

* Each upgrade step produces a commit with a breaking-change resolution diff.
* Steps with architectural decisions produce an ADR documenting options, trade-offs, and outcome.
* Steps with behavior worth specifying produce a spec in `docs/specs/`.
* `php artisan test --testsuite=Feature` passes at every commit — this validates the spec, not the ADR.
* The running app at `http://localhost:8080` is functional at every step.

## Pros and Cons of the Options

### Ground-up rebuild with SDD

* Good, because the ADRs would document a clean-target design with no legacy constraints.
* Bad, because no ADR would ever ask "should we pin the PHP platform version or build a custom Docker image?" — Laravel 13's defaults answer that before you think to ask.
* Bad, because the exercise produces a developer who can scaffold a new Laravel app and write ADRs for greenfield decisions, but cannot evolve a real system through breaking changes.
* Bad, because the existing codebase's decisions (why `implode($glue, $array)` was used, why `RedirectIfAuthenticated` has `...$guards`) are left as archeology, not resolved through methodology.

### Spec-first redesign

* Good, because specs would drive the entire implementation.
* Bad, because it throws away a working codebase with real bugs, real edge cases, and real technical debt — the very things specs need to protect against.
* Bad, because the exercise never practices the hardest part of SDD: evolving a spec alongside a changing codebase, not writing one from scratch.
