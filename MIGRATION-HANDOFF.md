---
schema: "kumwe-migration-handoff/v2"
artifact_kind: "framework_php"
migration_id: "KUMWE-MIG-2026-028"
change_set: "KUMWE-CS-2026-028"
state: "draft_pr_open"
target:
  repository: "https://github.com/kumwe/conversion-extension"
  artifact_identity: "kumwe/conversion-extension"
  canonical_namespace_or_abi: "Kumwe\\Conversion\\Contribution"
  branch: "codex/extraction-readiness-20260907"
  pull_request: "https://github.com/kumwe/conversion-extension/pull/4"
source:
  app:
    repository: "https://github.com/kumwe/app"
    baseline_commit: "24ecf956423c18933e824b43cea1bfb9127a79a9"
    examined_paths:
      - "src/BusinessRecord/Domain/MoneyRateProviderDefinition.php"
      - "src/Extension/Contribution/UnitConversionProviderDefinition.php"
      - "composer.json"
      - "docs/architecture/capability-index.md"
    old_namespace_roots:
      - "Kumwe\\App\\BusinessRecord\\Domain\\"
      - "Kumwe\\App\\Extension\\Contribution\\"
    capability_index_sha256: null
  semantic_inputs: []
  examined_dependencies:
    - "php ^8.5"
    - "kumwe/contribution 0.1.1"
    - "kumwe/conversion 0.1.3"
  active_related_pull_requests: []
framework_php:
  composer_package: "kumwe/conversion-extension"
  canonical_namespace: "Kumwe\\Conversion\\Contribution"
  public_api_manifest: "resources/public-api/v1.json"
  capability_manifest: "resources/capabilities/v1.json"
  service_map: "resources/service-map/v1.json"
  extracted_symbols:
    -
      old_fqcn: "Kumwe\\App\\BusinessRecord\\Domain\\MoneyRateProviderDefinition"
      new_fqcn: "Kumwe\\Conversion\\Contribution\\MoneyRateProviderDefinition"
      source_path: "src/BusinessRecord/Domain/MoneyRateProviderDefinition.php"
      target_path: "src/MoneyRateProviderDefinition.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "identifier"
        - "prices"
        - "priority"
        - "toArray"
      public_properties:
        - "currencies"
      public_constants:
        - "MAXIMUM_CURRENCIES"
      exceptions: []
      serialization_contract: "Explicit scalar projections documented in docs/public-api.md; PHP native serialization is not a durable wire or authority contract."
      compatibility: "Portable behavior is retained under the canonical namespace. Package-owned boundary and regression tests document deliberate validation and scheduling corrections."
    -
      old_fqcn: "Kumwe\\App\\Extension\\Contribution\\UnitConversionProviderDefinition"
      new_fqcn: "Kumwe\\Conversion\\Contribution\\UnitConversionProviderDefinition"
      source_path: "src/Extension/Contribution/UnitConversionProviderDefinition.php"
      target_path: "src/UnitConversionProviderDefinition.php"
      kind: "class"
      public_methods:
        - "__construct"
        - "fromArray"
        - "identifier"
        - "priority"
        - "relates"
        - "toArray"
      public_properties:
        - "units"
      public_constants:
        - "MAXIMUM_UNITS"
      exceptions: []
      serialization_contract: "Explicit scalar projections documented in docs/public-api.md; PHP native serialization is not a durable wire or authority contract."
      compatibility: "Portable behavior is retained under the canonical namespace. Package-owned boundary and regression tests document deliberate validation and scheduling corrections."
  consumers:
    app_code:
      - "src/BusinessRecord/Domain/MoneyRateProviderDefinition.php"
      - "src/BusinessRecord/Infrastructure/RuntimeMoneyRateProviderCatalog.php"
      - "src/BusinessRecord/Infrastructure/RuntimeUnitConversionProviderCatalog.php"
      - "src/Extension/Contribution/CanonicalManifestInterpreter.php"
      - "src/Extension/Contribution/UnitConversionProviderDefinition.php"
    configuration_and_di: []
    reflection_and_string_references:
      - "Recompute same-namespace, reflected and dynamically constructed names before App adoption; exact source inventory is evidence, not a complete dynamic reference proof."
    fixtures_and_examples:
      - "examples/consumer.php"
    external: []
  dependency_injection:
    mode: "direct"
    provider: null
    factories: []
    aliases: []
    service_lifetimes: []
    configuration_keys: []
    provider_absence_reason: "Immutable declarations and stateless helpers use direct construction; no injected runtime service is exported."
ownership:
  responsibility: "Portable immutable money-rate and unit-conversion provider declarations."
  non_responsibilities:
    - "Host trust and final authorization"
    - "Persistence, durable transactions, worker and transport lifecycle"
    - "App runtime adoption and native release publication"
  allowed_dependency_ceiling:
    - "php"
    - "kumwe/contribution"
    - "kumwe/conversion"
  implementation_owner: "kumwe/conversion-extension"
  next_consumer: "kumwe/app"
  public_manifests:
    -
      path: "resources/public-api/v1.json"
      sha256: "6e216713eba7207e8d8f95e59a2bf8322caada005360eefaea55e37baf503ce4"
    -
      path: "resources/capabilities/v1.json"
      sha256: "0f7ca11e3ae65449eacc781e1e7b0ac6c34bb73576aab9bfd5e3edd99d774799"
    -
      path: "resources/service-map/v1.json"
      sha256: "fc09fadbe366a7b1e8c415e557e02858893624b2b06ac34d3a961d82fccb14aa"
  intentionally_excluded:
    - "The two provider definition types are extracted. The former MoneyRateProviderRegistrar and UnitConversionProviderRegistrar were withdrawn before the reviewed App baseline; signed manifest declarations and ExtensionBindingRegistrar moneyRateProvider()/unitConversionProvider() in Extension SDK own registration. Conversion owns the provider interfaces and algorithms."
native_cpp: null
php_extension: null
tests:
  moved_or_added:
    - "tests/run.php"
  remain_in_app_or_consumer:
    - "tests/Unit/BusinessRecord/Application/MoneyRateProviderContributionTest.php"
    - "tests/Unit/BusinessRecord/Application/UnitConversionProviderContributionTest.php"
    - "tests/Unit/Extension/Contribution/UnitConversionProviderDeclarationTest.php"
  split_tests:
    - "Remove only library implementation assertions after verified App adoption; retain host wiring and composed behavior assertions."
  prohibited_duplicates:
    - "App must not retain unit tests of vendor-owned implementation internals after adoption."
  corpora: []
documentation:
  charter: "CHARTER.md"
  readme: "README.md"
  public_api: "docs/public-api.md"
  architecture: "docs/architecture.md"
  integration_or_consumer: "docs/integration.md"
  examples:
    - "examples/consumer.php"
  changelog_record: "CHANGELOG.md / 0.1.1"
release_expectations:
  version_policy: "Exact stable sibling package pins; preserve coherent released graphs until compatible successor releases exist."
  expected_artifact_types:
    - "Composer package archive"
    - "GitHub source archive"
  required_checks:
    - "composer check"
    - "Final hosted package CI"
    - "Machine handoff and consumer schema validation"
  required_registry_or_installer: "Composer"
  required_external_attestation: true
next_task:
  phase_name: "Review and verify the library successor release before separate App integration"
  permitted_only_when:
    - "Final package CI passes at the proposed head"
    - "Immutable package and all dependency releases are independently verified"
    - "Reconcile current App drift against the recorded source inventories"
  consumer_repository: "https://github.com/kumwe/app"
  dependency_or_native_change: "Install the exact independently verified successor; run Composer resolution, archive consumer gates and affected App integration tests before namespace removal."
  namespace_or_api_replacements:
    - "Kumwe\\App\\BusinessRecord\\Domain\\MoneyRateProviderDefinition -> Kumwe\\Conversion\\Contribution\\MoneyRateProviderDefinition"
    - "Kumwe\\App\\Extension\\Contribution\\UnitConversionProviderDefinition -> Kumwe\\Conversion\\Contribution\\UnitConversionProviderDefinition"
  files_to_update:
    - "composer.json"
    - "composer.lock"
    - "src/BusinessRecord/Domain/MoneyRateProviderDefinition.php"
    - "src/BusinessRecord/Infrastructure/RuntimeMoneyRateProviderCatalog.php"
    - "src/BusinessRecord/Infrastructure/RuntimeUnitConversionProviderCatalog.php"
    - "src/Extension/Contribution/CanonicalManifestInterpreter.php"
    - "src/Extension/Contribution/UnitConversionProviderDefinition.php"
    - "tests/Unit/BusinessRecord/Application/MoneyRateProviderContributionTest.php"
    - "tests/Unit/BusinessRecord/Application/UnitConversionProviderContributionTest.php"
    - "tests/Unit/Extension/Contribution/UnitConversionProviderDeclarationTest.php"
  files_to_remove:
    - "src/BusinessRecord/Domain/MoneyRateProviderDefinition.php"
    - "src/Extension/Contribution/UnitConversionProviderDefinition.php"
  tests_to_remove:
    - "Implementation-owned portions only, after the package behavior suite and App integration suite pass."
  tests_to_retain_or_add:
    - "tests/Unit/BusinessRecord/Application/MoneyRateProviderContributionTest.php"
    - "tests/Unit/BusinessRecord/Application/UnitConversionProviderContributionTest.php"
    - "tests/Unit/Extension/Contribution/UnitConversionProviderDeclarationTest.php"
  di_or_provisioning_changes:
    - "Use direct construction and supply canonical dependency values; no provider is required."
  capability_index_changes:
    - "Record ownership from the verified package capability and public API manifests."
  changelog_and_evidence_changes:
    - "Record exact source, package archive and dependency identities in the external release attestation and App integration ledger."
  verification_commands:
    - "composer check"
    - "Affected App integration suites"
    - "Complete App package governance gate"
concurrency:
  likely_conflict_files:
    - "App composer.json"
    - "App composer.lock"
    - "App provider configuration"
  related_migrations: []
  ownership_conflicts: []
  integration_train: null
  resolution_rule: "semantic-preservation"
governance:
  roadmap_source_sha256: "a202155ef1a65f5ab293d4f8397ebf4ac430db7f1e877c776bbe7851e6fe18d8"
  roadmap_refs: []
  non_roadmap_refs:
    - "NRM-2026-028"
  completion_claim: false
decisions:
  - "Validate direct constructor lists as strictly as parsed input and document the current registrar ownership."
  - "The two provider definition types are extracted. The former MoneyRateProviderRegistrar and UnitConversionProviderRegistrar were withdrawn before the reviewed App baseline; signed manifest declarations and ExtensionBindingRegistrar moneyRateProvider()/unitConversionProvider() in Extension SDK own registration. Conversion owns the provider interfaces and algorithms."
  - "Library behavior tests are package-owned. App changes, releases and external attestations are separate tasks."
blockers:
  - "Independent successor release verification and the final package gate remain necessary before App adoption."
---

# conversion-extension implementation handoff

## Migration/implementation summary

Validate direct constructor lists as strictly as parsed input and document the current registrar ownership. [PR #4](https://github.com/kumwe/conversion-extension/pull/4) contains this successor. The changelog version describes the proposed artifact; it is not a publication observation.

## Public API and responsibility

The two provider definition types are extracted. The former MoneyRateProviderRegistrar and UnitConversionProviderRegistrar were withdrawn before the reviewed App baseline; signed manifest declarations and ExtensionBindingRegistrar moneyRateProvider()/unitConversionProvider() in Extension SDK own registration. Conversion owns the provider interfaces and algorithms. Every exported member is recorded in resources/public-api/v1.json and documented in docs/public-api.md. The current surface contains 2 types. 2 types have recorded extraction provenance; package-native composition is identified separately.

## Capability reuse/semantic input review

The implementation consumes the exact canonical dependency contracts recorded in composer.json. Install the exact independently verified successor; run Composer resolution, archive consumer gates and affected App integration tests before namespace removal.

## Consumer inventory

The machine record lists actual source mappings, known consumer paths and concrete namespace replacements. resources/migration/consumer-inventory.json and resources/migration/source-map.json retain source digests where present. Dynamic references and same-namespace names must be searched again during adoption; the inventory does not imply that App has already switched ownership.

## Test ownership

Package tests own portable values, validation, service behavior, explicit construction and malformed-input regressions. The machine record identifies the source suites to split. Host persistence, transactions, authority, transport and operational integration stay in App. After verified adoption, remove duplicate library implementation assertions from App together with their legacy source.

## Next-task execution notes

Independent successor release verification and the final package gate remain necessary before App adoption. Install the exact independently verified successor; run Composer resolution, archive consumer gates and affected App integration tests before namespace removal. Run final source and clean archive gates before admitting the package; then update the App dependency lock, replace namespaces, retain host adapters and remove only the inventoried portable legacy implementations.

## Drift check

Reconcile the recorded source commit and per-file source digests with the current App before adoption. Recompute all public manifest hashes together. Keep actual release observations and final tested commit identities outside the tested source tree to avoid self-referential evidence.

## Validation recipe and observed local results

Run composer check with the documented PHP runtime and extensions. The review added and exercised the boundary regressions described in CHANGELOG.md. A final gate pass, remote CI status and immutable release verification are distinct observations; neither a proposed version nor this handoff attests publication. See docs/integration.md and the package check scripts for the exact archive and runtime recipe.
