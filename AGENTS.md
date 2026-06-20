# Project Conventions for AI Agents

This file documents project-specific conventions that AI agents must follow when working on this codebase.

## Artifact Model

Three layers with independent lifecycle rules:

1. **ADR** (immutable after approval) — decision record: context, options, trade-offs, rationale. Located in `docs/adr/`. File pattern: `adr_NNN_concise-name.md`. If a gap is discovered, write a new ADR. Never edit an approved ADR.

2. **Spec** (living) — behavior specification: interfaces, edge cases, acceptance criteria. Located in `docs/specs/`. Refined as implementation reveals gaps. A failed build means the spec was imprecise — fix the spec, then the code.

3. **Code** — validates the spec. A passing test suite means the spec is correct for now.

Links: Spec references ADR by number to surface constraints. Code references neither.

## ADR Quality Standards

- Every ADR must document a decision with genuine alternatives and non-trivial trade-offs. No mechanical-change ADRs.
- An ADR must be precise enough that an AI agent can implement from it.
- A failed build never means an ADR is wrong — it means a spec is imprecise or a decision gap exists (write a new ADR).
- ADRs with time-bound or context-dependent constraints should include a **Revisit Trigger** section describing the event or condition that should prompt reevaluation.

## Project Structure

- `docs/adr/` — Architectural Decision Records
- `docs/specs/` — Behavior Specifications
- `docs/BUILD.md` — Build instructions

## Technology Stack

- **Backend:** Laravel (currently on 10.x, migrating to 13.x)
- **Frontend (legacy):** Vue 2 + jQuery + Bootstrap 4 + Laravel Mix
- **Frontend (target):** Vite + Alpine.js + SCSS
- **Database:** SQLite
- **Runtime:** Docker (no host PHP)
- **Testing:** PHPUnit 9.x (Feature tests)
