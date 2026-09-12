# Host integration

Install an exact independently verified package version. The source and consumer inventories in
`resources/migration/` preserve compatibility provenance. Import canonical types directly; do not add
aliases, wrappers, dual PSR-4 roots or shadow implementations.

Construct either provider definition from code or strict manifest data. Pass actual
`MoneyConversionRequest` or `UnitConversionRequest` objects to its eligibility predicate before
executing host-owned provider implementations. Preserve signed-manifest ownership, binding collision,
reconciliation, active-generation, revocation, recovery and provider-attribution checks through the SDK SPI.
This package supplies no host registry or runtime factory.

When changing a consumer's exact package pin, inspect its current source, resolve dependencies and run
affected composition/lifecycle checks. Retain host assertions listed in the test ownership inventory;
portable implementation assertions belong to package tests. Record the selected version and independent
source/artifact identities in the consumer's dependency and verification evidence.
