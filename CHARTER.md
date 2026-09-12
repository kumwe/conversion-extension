# Conversion Extension ownership charter

Conversion Extension owns typed money-rate and unit-conversion provider contribution declarations under
`Kumwe\Conversion\Contribution`.

## Host and dependency responsibilities

Conversion algorithms, rate data, unit catalogues, native PHP bindings, provider selection and trusted
activation remain outside this package. Production package code never imports Kumwe App.

## Package contract

The package owns portable behavior and boundary tests, API manifests, archive verification and consumer
examples. [The release contract record](docs/release-record.md) preserves source provenance, symbol mappings,
compatibility requirements and independent verification obligations.

Consumers use independently verified immutable releases and retain host binding and lifecycle checks
when changing an exact package pin. Each portable symbol has one canonical owner. Namespace aliases,
copied vendor implementations, duplicate registrars and silent runtime fallbacks are prohibited.
