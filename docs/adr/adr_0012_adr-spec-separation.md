# ADR/Spec Separation

## Status

Approved

## Context and Problem Statement

During review of ADR-0001, we identified that architectural decision records and behavior specifications serve different purposes with different lifecycle requirements:

- **ADRs** document *why* a decision was made — the context, options considered, trade-offs evaluated, and the outcome. They should be immutable after approval because they record a historical decision. Editing an ADR after the fact undermines its value as a decision record.

- **Specs** document *what* the system should do — concrete interfaces, behavior, edge cases, acceptance criteria. They must evolve as implementation reveals gaps, edge cases missed during design, and feedback from real usage.

Trying to serve both purposes in one document creates tension: either the spec section is frozen by the ADR's immutability (bad, because specs need to evolve), or the ADR section is treated as editable (bad, because decision records lose integrity).

How should we structure these two artifact types so each serves its purpose without compromising the other?

## Decision Drivers

- ADRs must be immutable after approval (historical decision records)
- Specs must be living documents that evolve with implementation feedback
- An AI agent needs a single authoritative source for "what should this do" — the spec
- An agent or developer needs to find the "why" behind a spec's constraints — the ADR
- The relationship between specs and ADRs must be discoverable

## Considered Options

### A. Separate directories: `docs/adr/` + `docs/specs/`

ADRs in `docs/adr/`, specs in `docs/specs/`. Specs reference ADRs by number to document decision constraints. ADRs do not reference specs (decisions are independent of implementation details).

**Links are one-directional: spec → ADR.** A spec may reference multiple ADRs. An ADR is never updated to reference a spec.

### B. Combined: single file with ADR and spec sections

One file per feature: ADR section (top, immutable after approval) + spec section (bottom, living). ADR section links to other ADRs. Spec section links to other specs.

### C. Single directory with naming convention

All documents in `docs/` with prefix convention: `adr_NNN_*` and `spec_NNN_*`. ADRs and specs interleaved in one flat namespace, distinguished only by prefix.

## Decision Outcome

**Chosen: A — Separate directories.**

`docs/adr/` for decision records. `docs/specs/` for behavior specifications. Specs reference ADRs by number to surface constraints. ADRs do not reference specs.

### Consequences

* Good, because each artifact type has its own lifecycle rules (ADR immutable, spec living).
* Good, because an agent looking for behavior criteria reads one spec file — no need to filter out ADR content.
* Good, because a reader investigating a spec change can follow ADR references to understand why constraints exist.
* Good, because ADR numbering is independent of spec numbering — no gaps if a spec is removed.
* Bad, because it's an additional cognitive split — two places to look instead of one.
* Bad, because spec→ADR references must be kept correct when ADRs are renumbered (rare but possible).
* Bad, because a spec that references many ADRs may require cross-referencing across two directories.

### Confirmation

* `docs/adr/` contains only ADRs (numbered sequentially).
* `docs/specs/` contains only specs (numbered independently if at all).
* Every spec that references an ADR does so by number with a discoverable link.
- Option B (combined) was rejected because it either freezes specs at ADR-approval time or weakens ADR immutability.
