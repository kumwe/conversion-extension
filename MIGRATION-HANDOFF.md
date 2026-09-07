---
schema: kumwe-migration-handoff/v2
artifact_kind: framework_php
migration_id: KUMWE-MIG-2026-028
change_set: KUMWE-CS-2026-028
state: draft_pr_open
target:
  repository: https://github.com/kumwe/conversion-extension
  artifact_identity: kumwe/conversion-extension
  canonical_namespace_or_abi: Kumwe\Conversion\Contribution
  branch: agent/extract-conversion-extension-v2
  pull_request: https://github.com/kumwe/conversion-extension/pull/1
source:
  app:
    repository: https://github.com/kumwe/app
    baseline_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    examined_paths:
    - src
    - tests
    - config
    - bootstrap
    - examples
    - tools
    - docs
    - composer.json
    old_namespace_roots:
    - Kumwe\App\BusinessRecord\Domain
    - Kumwe\App\Extension\Contribution
  semantic_inputs: []
  examined_dependencies:
  - Canonical Contribution 0.1.0 owner, explicit SurfaceIdentifierPolicy and definition API.
  - Canonical Conversion 0.1.2 request, value and unit pattern API; independent evidence still required.
  active_related_pull_requests: []
framework_php:
  composer_package: kumwe/conversion-extension
  public_api_manifest: resources/public-api/v1.json
  capability_manifest: resources/capabilities/v1.json
  service_map: resources/service-map/v1.json
  source_map: resources/migration/source-map.json
  consumer_inventory: resources/migration/consumer-inventory.json
  test_ownership: resources/migration/test-ownership.json
  canonical_namespace: Kumwe\Conversion\Contribution
  extracted_symbols:
  - old_fqcn: Kumwe\App\BusinessRecord\Domain\MoneyRateProviderDefinition
    new_fqcn: Kumwe\Conversion\Contribution\MoneyRateProviderDefinition
    source_repository: https://github.com/kumwe/app
    source_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    source_path: src/BusinessRecord/Domain/MoneyRateProviderDefinition.php
    source_sha256: 23e8835d04cc22473b5eb0e9de4a0c96c42322a1958bb1a373e050e12d0112fe
    target_path: src/MoneyRateProviderDefinition.php
    target_sha256: 1e431e9610ffb61f752edab78cd723e70277d5ab94eda0922bce4dc5cd1355ca
    kind: class
    public_methods:
    - __construct
    - fromArray
    - identifier
    - prices
    - priority
    - toArray
    public_properties:
    - currencies
    public_constants:
    - MAXIMUM_CURRENCIES
    exceptions:
    - InvalidArgumentException
    serialization_contract: Method PHPDoc and docs/public-api.md; deterministic toArray when present.
    compatibility: Canonical dependency and namespace migration; observable declaration semantics preserved.
  - old_fqcn: Kumwe\App\Extension\Contribution\UnitConversionProviderDefinition
    new_fqcn: Kumwe\Conversion\Contribution\UnitConversionProviderDefinition
    source_repository: https://github.com/kumwe/app
    source_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    source_path: src/Extension/Contribution/UnitConversionProviderDefinition.php
    source_sha256: 0a3150f2d0be368534e9ef6c3975df37b326a54dabac97af113b02c425e6436c
    target_path: src/UnitConversionProviderDefinition.php
    target_sha256: 1cc1b644d493185dc421bda689ebe25100ec5af7910e5bede49323269dce2488
    kind: class
    public_methods:
    - __construct
    - fromArray
    - identifier
    - priority
    - relates
    - toArray
    public_properties:
    - units
    public_constants:
    - MAXIMUM_UNITS
    exceptions:
    - InvalidArgumentException
    serialization_contract: Method PHPDoc and docs/public-api.md; deterministic toArray when present.
    compatibility: Canonical dependency and namespace migration; observable declaration semantics preserved.
  consumers:
    app_code:
    - src/Extension/Contribution/CanonicalManifestInterpreter.php
    - src/Extension/Contribution/UnitConversionProviderDefinition.php
    - src/BusinessRecord/Domain/MoneyRateProviderDefinition.php
    - src/BusinessRecord/Infrastructure/RuntimeMoneyRateProviderCatalog.php
    - src/BusinessRecord/Infrastructure/RuntimeUnitConversionProviderCatalog.php
    configuration_and_di: []
    reflection_and_string_references: []
    fixtures_and_examples:
    - tests/Unit/Extension/Contribution/UnitConversionProviderDeclarationTest.php
    - tests/Unit/BusinessRecord/Application/MoneyRateProviderContributionTest.php
    - tests/Unit/BusinessRecord/Application/UnitConversionProviderContributionTest.php
    external:
    - repository: https://github.com/kumwe/extension-sdk
      files: []
  dependency_injection:
    mode: direct
    provider: null
    factories: []
    aliases: []
    service_lifetimes: []
    configuration_keys: []
    provider_absence_reason: Immutable declarations and directly constructed stateless conformance validator; no extracted
      injected runtime service.
release:
  publication_authorized: false
  release_verified: false
  app_adoption_authorized: false
ownership:
  responsibility: Typed money-rate and unit-conversion provider contribution definitions.
  non_responsibilities:
  - Host trust and active contribution admission
  - Authorization enforcement, rendering, navigation, persistence and transactions
  - Provider implementation storage, dispatch and conversion algorithms
  allowed_dependency_ceiling:
  - kumwe/contribution
  - kumwe/conversion
  implementation_owner: kumwe/conversion-extension
  next_consumer: kumwe/app
  public_manifests:
  - path: resources/public-api/v1.json
    sha256: 8d07e63d9db858e1bb2239f3938e1573e3ff7e817df3ae9fb7b690ac953e6195
  - path: resources/capabilities/v1.json
    sha256: 17dd4ae13cc6e1d9bff0816f38a00094fb81ccbfa29a73a67cba8cc141cabb01
  - path: resources/service-map/v1.json
    sha256: 1a65241cd678f89ebf3a18558bc8227c0c48a0973665c114c6039596c8cf9c24
  intentionally_excluded:
  - src/Extension/Contribution/MoneyRateProviderRegistrar.php
  - src/Extension/Contribution/UnitConversionProviderRegistrar.php
native_cpp: null
php_extension: null
tests:
  moved_or_added:
  - path: tests/run.php
    behavior: Strict declaration parsing, bounds, normalization, serialization and canonical dependency contracts; closed
      currency/unit request eligibility in both directions.
  remain_in_app_or_consumer: &id001
  - tests/Unit/BusinessRecord/Application/MoneyRateProviderContributionTest.php
  - tests/Unit/BusinessRecord/Application/UnitConversionProviderContributionTest.php
  split_tests:
  - path: tests/Unit/Extension/Contribution/UnitConversionProviderDeclarationTest.php
    sha256: d3e0076120d84ebb527aeace9f65e25e4955371bca540f4a750c64571a5a26b1
  - path: tests/Unit/BusinessRecord/Application/MoneyRateProviderContributionTest.php
    sha256: fcb38ca5a6e27667492c4ba08eb7a84237dc2961ba22496b4786671015448326
  prohibited_duplicates:
  - Do not retain vendor-class implementation assertions in App after Phase 2; preserve host composition assertions.
  corpora: []
documentation:
  charter: CHARTER.md
  readme: README.md
  public_api: docs/public-api.md
  architecture: docs/architecture.md
  integration_or_consumer: docs/integration.md
  examples:
  - examples/consumer.php
  changelog_record: CHANGELOG.md#unreleased
release_expectations:
  version_policy: SemVer; maintainer chooses first version after review. No release is claimed.
  expected_artifact_types:
  - Composer ZIP distribution
  required_checks:
  - '@lint'
  - '@api'
  - '@architecture'
  - '@analyse'
  - '@cs'
  - '@test'
  - '@examples'
  - '@security'
  - '@clean-consumer'
  required_registry_or_installer: Composer registry with immutable dist source
  required_external_attestation: true
next_task:
  phase_name: Complete and review Phase 1, then independently verify the immutable release before separate App adoption
  permitted_only_when:
  - Phase 1 release automation and hosted final-head checks pass
  - All exact dependencies have independent release verification
  - Human merge and automation release complete
  - Separate RELEASE-ATTESTATION.yaml status release-verified
  consumer_repository: https://github.com/kumwe/app
  dependency_or_native_change: Add exact verified kumwe/conversion-extension release; remove old App definitions.
  namespace_or_api_replacements:
  - old_fqcn: Kumwe\App\BusinessRecord\Domain\MoneyRateProviderDefinition
    new_fqcn: Kumwe\Conversion\Contribution\MoneyRateProviderDefinition
  - old_fqcn: Kumwe\App\Extension\Contribution\UnitConversionProviderDefinition
    new_fqcn: Kumwe\Conversion\Contribution\UnitConversionProviderDefinition
  files_to_update:
  - src/Extension/Contribution/CanonicalManifestInterpreter.php
  - src/Extension/Contribution/UnitConversionProviderDefinition.php
  - src/BusinessRecord/Domain/MoneyRateProviderDefinition.php
  - src/BusinessRecord/Infrastructure/RuntimeMoneyRateProviderCatalog.php
  - src/BusinessRecord/Infrastructure/RuntimeUnitConversionProviderCatalog.php
  - tests/Unit/Extension/Contribution/UnitConversionProviderDeclarationTest.php
  - tests/Unit/BusinessRecord/Application/MoneyRateProviderContributionTest.php
  - tests/Unit/BusinessRecord/Application/UnitConversionProviderContributionTest.php
  files_to_remove:
  - src/BusinessRecord/Domain/MoneyRateProviderDefinition.php
  - src/Extension/Contribution/UnitConversionProviderDefinition.php
  tests_to_remove:
  - Portable implementation assertions in mixed source tests listed by resources/migration/test-ownership.json; do not delete
    host portions.
  tests_to_retain_or_add: *id001
  di_or_provisioning_changes:
  - No package DI provider; preserve App-owned composition and registrar services.
  capability_index_changes:
  - Update App capability index references for moved types without claiming composed roadmap completion.
  changelog_and_evidence_changes:
  - Update App changelog and migration ledger under KUMWE-CS-2026-028
  verification_commands:
  - composer check
  - Run full affected App integration and delivery checks after adoption.
concurrency:
  likely_conflict_files:
  - composer.json
  - composer.lock
  - config/capabilities.php
  - src/Extension/Contribution/CanonicalManifestInterpreter.php
  - src/Extension/Contribution/UnitConversionProviderDefinition.php
  - src/BusinessRecord/Domain/MoneyRateProviderDefinition.php
  - src/BusinessRecord/Infrastructure/RuntimeMoneyRateProviderCatalog.php
  - src/BusinessRecord/Infrastructure/RuntimeUnitConversionProviderCatalog.php
  - tests/Unit/Extension/Contribution/UnitConversionProviderDeclarationTest.php
  - tests/Unit/BusinessRecord/Application/MoneyRateProviderContributionTest.php
  - tests/Unit/BusinessRecord/Application/UnitConversionProviderContributionTest.php
  related_migrations:
  - Contribution
  - Conversion
  ownership_conflicts: []
  integration_train: null
  resolution_rule: semantic-preservation
governance:
  classification: enabling-refactor
  roadmap_refs: []
  non_roadmap_refs:
  - NRM-2026-028
  completion_claim: false
decisions:
- Two definition types extracted; two registrar types retained in host because they own authority.
- No aliases, vendor copies, host registrars or empty ConfigProvider.
- Local source aliases are preliminary development verification only.
blockers:
- Release-on-record automation and integrity tests not implemented here.
- Hosted PHP/platform matrix and final committed-head gate evidence pending.
- Immutable dependency verification incomplete; publication and App adoption blocked.
- Complete external security audit pending.
---



# Phase 1 handoff

2 source types extracted; planning count was 4. The two registrar types remain App-owned because their closure stores executable providers and requires trusted active contribution authority.

The draft PR URL is observed, not predicted. No merge, tag, release, source digest for a future commit, artifact publication, or App adoption is claimed. The source map contains one row per extracted symbol with source and target hashes. The public API manifest enumerates all stable methods, parameter names/defaults, return types, readonly properties, enum cases and public constants. Capability and empty service manifests describe the actual extracted runtime.

## Ownership and dependency decisions

2 source types extracted; planning count was 4. The two registrar types remain App-owned because their closure stores executable providers and requires trusted active contribution authority.

MoneyRateProviderDefinition and UnitConversionProviderDefinition implement the canonical ContributionDefinition interface and accept canonical Conversion request types in their eligibility predicates. Conversion remains the owner of request, exact-decimal, rounding, and unit spelling semantics. Neither definition stores providers, selects providers, admits trust, or activates extensions.

The candidate brief expected registrar extraction and DI providers. Refreshed source closure shows both registrars require App-owned registries/trusted activation. They remain host-owned; inventing an empty provider would misrepresent the extracted runtime. Service and provider lists are intentionally empty and checked by the architecture gate.

The production token guard permits only the documented dependency namespaces and rejects host/native/container loading. The source map records exact source commits, paths, source digests, new symbols, target paths, and current target digests. Consumer inventory records file-level migration references without modifying App.

## Retained source files

- `src/Extension/Contribution/MoneyRateProviderRegistrar.php`
- `src/Extension/Contribution/UnitConversionProviderRegistrar.php`

Their implementation, authority and tests remain App-owned. No copying, replacement registrar, fallback, or empty DI provider is introduced.

## Verification and blockers

The source implementation passes the behavior suite and maximum-level static analysis locally. PHP syntax, API manifests, dependency guard, PSR-12, example and archive-consumer commands are reproducible from repository scripts. Local dependency inputs are development source snapshots with explicit dev-source aliases, not immutable dependency attestations. Exact target dependency coordinates are in composer.json. Contribution 0.1.0 has separately verified evidence; Conversion 0.1.2 requires its applicable independently verified legacy or v2 evidence before publication.

Pending: release-on-record automation and its integrity tests, supported-platform hosted CI results, complete security audit, immutable dependency verification, and all final committed-head package/archive/consumer gates. The current draft must not be marked review-ready based solely on local tests. Parent coordination may add release automation and final-head evidence separately without changing these ownership decisions.

## Test ownership and Phase 2

Install an exact independently verified release before changing App. See `resources/migration/source-map.json` for every namespace replacement and `consumer-inventory.json` for the inspected file-level references. Do not add aliases, wrappers, dual PSR-4 roots, or shadow implementations.

Construct either provider definition from code or strict manifest data. Pass actual MoneyConversionRequest or UnitConversionRequest objects to the eligibility predicate before dispatching to host-owned provider implementations. Preserve the original registrar owner checks, collisions, manifest reconciliation, active-generation checks, revocation, recovery behavior, and provider attribution. This package supplies no host registry or runtime factory.

In Phase 2, reconcile App changes since the captured baseline, update every affected import and signature to the mapped canonical owner, delete the extracted App definitions, and move only portable implementation assertions out of mixed App tests. Retain host composition and lifecycle assertions listed in `resources/migration/test-ownership.json`. Update the App dependency lock, migration ledger, capability index and changelog; run affected host and integration-train gates. No App files were changed here.


The test ownership manifest identifies original App test inputs by hash. Host-owned lifecycle/registry/rendering portions remain in App; portable assertions are removed from App only in the separate release-verified adoption task. File-level consumer references are in the inventory; reconcile newly changed references before deletion.

## Change and roadmap evidence

KUMWE-CS-2026-028 / KUMWE-MIG-2026-028 / NRM-2026-028. See CHANGELOG.md. This is an enabling refactor, not evidence that lifecycle admission, trust revocation, graphical parity, conversion provider activation, or any composed App roadmap gate is complete.

## Drift check and source evidence

Before Phase 2, recompute source SHA-256 for each mapped App file and compare the captured baseline. Route new portable behavior through its owning package and a separately verified release first. Re-scan `resources/migration/consumer-inventory.json`; preserve concurrently added host behavior and resolve conflicts semantically. No silent source overwrite is permitted.

## Validation recipe and observed local results

`php tests/run.php`, `php tools/lint.php`, `php tools/public-api.php`, `php tools/architecture.php`, PHPStan `analyse --no-progress` at maximum level, and PHP_CodeSniffer with the checked-in PSR-12 configuration are the local package commands. The actual runtime is PHP 8.5.10 NTS. Clean-consumer verification installs the built ZIP with no dev packages and authoritative classmap, then executes the installed example with real dependency types. Its explicit local `dev-source` aliases keep preliminary composition evidence distinct from independent release evidence. The final committed-head tests and published artifact identity remain external verification responsibilities.

## Source candidate CI

The `Source candidate gate` checks this PR using the explicitly recorded development dependency coordinates in `resources/source-ci-dependencies.json`. It installs QA tools, executes the package source gate and validates a fresh archive consumer. Development branches are not represented as released versions. This workflow is not the common immutable-release Package gate and cannot authorize publication or adoption.
