---
schema: kumwe-package-release-record/v1
artifact_kind: framework_php
migration_id: KUMWE-MIG-2026-028
change_set: KUMWE-CS-2026-028
target:
  repository: https://github.com/kumwe/conversion-extension
  artifact_identity: kumwe/conversion-extension
  canonical_namespace_or_abi: Kumwe\Conversion\Contribution\
source:
  app:
    repository: https://github.com/kumwe/app
    baseline_commit: 24ecf956423c18933e824b43cea1bfb9127a79a9
    examined_paths:
      - src/BusinessRecord/Domain/MoneyRateProviderDefinition.php
      - src/Extension/Contribution/UnitConversionProviderDefinition.php
      - composer.json
      - docs/architecture/capability-index.md
    old_namespace_roots:
      - Kumwe\App\BusinessRecord\Domain\
      - Kumwe\App\Extension\Contribution\
    capability_index_sha256: 8fb2a8680bed6ac1456183bc9e48fe040194923b6d1b3331f04cea28bd5a9b2f
  semantic_inputs: []
  examined_dependencies:
    - php ^8.5
    - kumwe/contribution 0.1.1
    - kumwe/conversion 0.1.5
framework_php:
  composer_package: kumwe/conversion-extension
  canonical_namespace: Kumwe\Conversion\Contribution\
  public_api_manifest: resources/public-api/v1.json
  capability_manifest: resources/capabilities/v1.json
  service_map: resources/service-map/v1.json
  extracted_symbols:
    - old_fqcn: Kumwe\App\BusinessRecord\Domain\MoneyRateProviderDefinition
      new_fqcn: Kumwe\Conversion\Contribution\MoneyRateProviderDefinition
      source_path: src/BusinessRecord/Domain/MoneyRateProviderDefinition.php
      target_path: src/MoneyRateProviderDefinition.php
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
      serialization_contract: Use the explicit scalar projections documented in docs/public-api.md; native serialization conveys no authority.
      compatibility: Constructors and parsers enforce the same bounds; the host owns trusted provider admission.
    - old_fqcn: Kumwe\App\Extension\Contribution\UnitConversionProviderDefinition
      new_fqcn: Kumwe\Conversion\Contribution\UnitConversionProviderDefinition
      source_path: src/Extension/Contribution/UnitConversionProviderDefinition.php
      target_path: src/UnitConversionProviderDefinition.php
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
      serialization_contract: Use the explicit scalar projections documented in docs/public-api.md; native serialization conveys no authority.
      compatibility: Constructors and parsers enforce the same bounds; the host owns trusted provider admission.
  consumers:
    app_code:
      - src/BusinessRecord/Domain/MoneyRateProviderDefinition.php
      - src/BusinessRecord/Infrastructure/RuntimeMoneyRateProviderCatalog.php
      - src/BusinessRecord/Infrastructure/RuntimeUnitConversionProviderCatalog.php
      - src/Extension/Contribution/CanonicalManifestInterpreter.php
      - src/Extension/Contribution/UnitConversionProviderDefinition.php
    configuration_and_di: []
    reflection_and_string_references:
      - Reconcile same-namespace, reflected and constructed names against the current host; inventories cannot prove dynamic coverage.
    fixtures_and_examples:
      - examples/consumer.php
    external: []
  dependency_injection:
    mode: direct
    provider: null
    factories: []
    aliases: []
    service_lifetimes: []
    configuration_keys: []
    provider_absence_reason: Immutable declarations and stateless helpers use direct construction; no injected runtime service is exported.
ownership:
  responsibility: Portable immutable money-rate and unit-conversion provider declarations.
  non_responsibilities:
    - Host trust and final authorization
    - Persistence, durable transactions, worker and transport lifecycle
    - App runtime adoption and native release publication
  allowed_dependency_ceiling:
    - php
    - kumwe/contribution
    - kumwe/conversion
  implementation_owner: kumwe/conversion-extension
  next_consumer: kumwe/app
  public_manifests:
    - path: resources/public-api/v1.json
      sha256: 5520fe30fc1e99030849e14806396375daa18c53a42a17094248ba5cdd56fc97
    - path: resources/capabilities/v1.json
      sha256: 9bbd3bb0a65ac5f21668b5ed75125b0e6e8705b124bca8caf96c510aac73904b
    - path: resources/service-map/v1.json
      sha256: f07fea2bd537ec495a2538030d4c370c22f37ccac2c14e2d04a01d419f41cba4
  intentionally_excluded:
    - Signed manifest declarations and ExtensionBindingRegistrar::moneyRateProvider()/unitConversionProvider() own registration.
    - MoneyRateProviderRegistrar and UnitConversionProviderRegistrar are withdrawn contracts and must not be recreated.
    - Conversion owns provider interfaces, request values and exact conversion algorithms.
native_cpp: null
php_extension: null
tests:
  moved_or_added:
    - tests/run.php
    - tools/governance/test.cjs
    - tools/clean-consumer.php
  remain_in_app_or_consumer:
    - tests/Unit/BusinessRecord/Application/MoneyRateProviderContributionTest.php
    - tests/Unit/BusinessRecord/Application/UnitConversionProviderContributionTest.php
    - tests/Unit/Extension/Contribution/UnitConversionProviderDeclarationTest.php
  split_tests:
    - Keep package implementation assertions here and host wiring, trust and composed behavior assertions in Core.
  prohibited_duplicates:
    - App must not retain unit tests of vendor-owned implementation internals after adoption.
  corpora: []
documentation:
  charter: CHARTER.md
  readme: README.md
  public_api: docs/public-api.md
  architecture: docs/architecture.md
  integration_or_consumer: docs/integration.md
  examples:
    - examples/consumer.php
  changelog_record: CHANGELOG.md / 0.1.4
release_expectations:
  version_policy: Pin exact stable versions; preserve coherent released dependency graphs until verified successors are available.
  expected_artifact_types:
    - Composer package archive
    - GitHub source archive
  required_checks:
    - composer check
    - Final hosted Package gate
    - Full release record and canonical manifest schemas
  required_registry_or_installer: Composer
  required_external_attestation: true
governance:
  completion_claim: false
decisions:
  - Direct constructors and parsed declarations enforce identical validation bounds.
  - Signed contributions and ExtensionBindingRegistrar own registration; withdrawn registrar interfaces must not be recreated.
  - Conversion owns provider interfaces and conversion semantics; this package owns only typed contribution definitions.
  - Core owns trust, provider selection, runtime binding and lifecycle tests.
blockers: []
consumer_contract:
  permitted_only_when:
    - The final package gate passes for the exact proposed release source.
    - The immutable package and every dependency release are independently verified.
    - Current host integration tests pass and source inventories are reconciled against current Core.
  consumer_repository: https://github.com/kumwe/app
  dependency_or_native_change: Pin the exact verified release; regenerate composer.lock and pass archive and affected Core integration gates.
  namespace_or_api_replacements:
    - Kumwe\App\BusinessRecord\Domain\MoneyRateProviderDefinition -> Kumwe\Conversion\Contribution\MoneyRateProviderDefinition
    - Kumwe\App\Extension\Contribution\UnitConversionProviderDefinition -> Kumwe\Conversion\Contribution\UnitConversionProviderDefinition
  files_to_update:
    - composer.json
    - composer.lock
    - src/BusinessRecord/Domain/MoneyRateProviderDefinition.php
    - src/BusinessRecord/Infrastructure/RuntimeMoneyRateProviderCatalog.php
    - src/BusinessRecord/Infrastructure/RuntimeUnitConversionProviderCatalog.php
    - src/Extension/Contribution/CanonicalManifestInterpreter.php
    - src/Extension/Contribution/UnitConversionProviderDefinition.php
    - tests/Unit/BusinessRecord/Application/MoneyRateProviderContributionTest.php
    - tests/Unit/BusinessRecord/Application/UnitConversionProviderContributionTest.php
    - tests/Unit/Extension/Contribution/UnitConversionProviderDeclarationTest.php
  files_to_remove:
    - src/BusinessRecord/Domain/MoneyRateProviderDefinition.php
    - src/Extension/Contribution/UnitConversionProviderDefinition.php
  tests_to_remove:
    - Implementation-owned portions only, after the package behavior suite and App integration suite pass.
  tests_to_retain_or_add:
    - tests/Unit/BusinessRecord/Application/MoneyRateProviderContributionTest.php
    - tests/Unit/BusinessRecord/Application/UnitConversionProviderContributionTest.php
    - tests/Unit/Extension/Contribution/UnitConversionProviderDeclarationTest.php
  di_or_provisioning_changes:
    - Use direct construction and supply canonical dependency values; no provider is required.
  capability_index_changes:
    - Record ownership from the verified package capability and public API manifests.
  changelog_and_evidence_changes:
    - Record exact source, package archive and dependency identities in the external release attestation and App integration ledger.
  verification_commands:
    - composer governance:install
    - composer check
    - Affected App integration suites
    - Complete App package governance gate
---

# Conversion Extension release contract

## Package contract

This record describes the shipped ownership, public manifests and consumer obligations of
`kumwe/conversion-extension`. The package provides immutable money-rate and unit-conversion provider declarations.
Source mappings remain compatibility evidence for Core integration. Publication and independent release verification
are established by external release evidence.

## Public API and responsibility

The public types are `MoneyRateProviderDefinition` and `UnitConversionProviderDefinition` in
`Kumwe\Conversion\Contribution\`. They validate, normalize and project declarations without executing providers.
Their complete members are recorded in [the public API manifest](../resources/public-api/v1.json) and
[API documentation](public-api.md). Conversion owns provider interfaces and exact conversion semantics.

Signed contribution declarations and Extension SDK's `ExtensionBindingRegistrar` own provider registration.
The withdrawn `MoneyRateProviderRegistrar` and `UnitConversionProviderRegistrar` must not be recreated.

## Dependencies and semantic inputs

The package requires PHP 8.5, Contribution 0.1.1 and Conversion 0.1.5. Exact constraints live in
[composer.json](../composer.json). [Dependency readiness](../resources/release-readiness.json) records release
coordinates; each immutable dependency still requires independent verification before consumer admission.

## Consumer contract

Core interprets signed `contributions.integration.rate_providers` and `contributions.integration.unit_converters`
and binds implementations through `ExtensionBindingRegistrar::moneyRateProvider()` and
`ExtensionBindingRegistrar::unitConversionProvider()`. Core owns trust, collision rules, generation admission,
executable binding storage, selection, activation, revocation and recovery.

The machine record retains concrete namespace mappings and known consumer paths. Reconcile these against current Core,
including reflection and dynamic references, before replacing host-local definitions. See
[the integration contract](integration.md) and [consumer inventory](../resources/migration/consumer-inventory.json).

## Test ownership

Package tests own immutable declaration behavior, constructor/parser parity, bounds, normalization, matching and invalid
input refusal. Core retains signed-manifest interpretation, binding reconciliation, provider attribution, trust and
lifecycle tests. Remove duplicate host tests of package internals only when the verified package and Core suites pass.

## Consumer verification

Install exact independently verified package and dependency releases, regenerate the Composer lock and verify installed
archives. Run the affected Core suites and complete package governance gate before removing inventoried host definitions.
The installed example accepts the consumer autoload path; its successful execution and missing-path refusal remain in
the clean archive gate. [Verification instructions](verification.md) describe the supported commands.

## Compatibility and drift

Reconcile recorded source commits and per-file digests with current Core. Maintain namespace and member mappings with the
public API, and recompute all three manifest hashes together. Preserve immutable artifact identities and external
attestations; a version heading or this record alone cannot prove publication or Core adoption.

## Validation

Run `composer governance:install`, `composer install` and `composer check`. The gate requires complete schemas,
17 independent malformed-document regressions, source/API/documentation consistency, static analysis, runtime examples,
dependency readiness, release integrity and an isolated production archive consumer. Hosted CI supplies the final
`Package gate` result for the exact tested commit.
