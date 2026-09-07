# conversion-extension ownership charter

Change set: KUMWE-CS-2026-028. Migration: KUMWE-MIG-2026-028.
Non-roadmap reference: NRM-2026-028; extraction is an enabling refactor.

## Responsibility

Typed money-rate and unit-conversion provider contribution declarations under the canonical namespace `Kumwe\Conversion\Contribution`.

## Retained host responsibilities

Conversion algorithms, rate data, unit catalogues, native PHP bindings, provider selection, and trusted activation remain outside this package. Production code never imports Kumwe App.

## Delivery boundary

This branch owns Phase 1 package implementation and its behavior, boundary, conformance, public API, archive, and consumer tests. The source closure and exact old-to-new mapping are recorded in the migration handoff. App remains unchanged until separately verified immutable releases permit adoption. Dependencies that have not passed independent release verification are explicit publication blockers.

Package publication and consumer adoption require the reviewed release protocol; this branch does not merge, tag, or publish artifacts. Each portable symbol has one eventual canonical owner. Namespace aliases, copied vendor implementations, and silent runtime fallbacks are prohibited.
