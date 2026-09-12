# Kumwe Conversion Extension

[![Latest version][version-badge]][package]
[![Conversion Extension CI][ci-badge]][ci]
[![PHP requirement][php-badge]][package]
[![License][license-badge]](LICENSE)

Two immutable contribution definitions under `Kumwe\Conversion\Contribution`:
`MoneyRateProviderDefinition` and `UnitConversionProviderDefinition`. They connect Contribution
provider declarations to canonical Conversion requests. “Extension” means a business contribution.

## Installation

```bash
composer require kumwe/conversion-extension:0.1.4
```

Requires PHP `^8.5`, Contribution 0.1.1 and Conversion 0.1.5. Pre-1.0 consumers pin an independently
verified exact version. Dependencies resolve through Packagist.

```php
use Kumwe\Conversion\Contribution\MoneyRateProviderDefinition;
use Kumwe\Conversion\Contribution\UnitConversionProviderDefinition;

$rates = new MoneyRateProviderDefinition('acme.rates.ecb', ['USD', 'EUR'], 3);
$units = new UnitConversionProviderDefinition('acme.units.trade', ['case', 'unit']);
$manifest = $rates->toArray();
$restored = MoneyRateProviderDefinition::fromArray($manifest);
```

Provider claims contain 1–64 entries, are deduplicated and sorted, and use priorities from -128 through 127.
`prices(MoneyConversionRequest)` and `relates(UnitConversionRequest)` match only requests whose two ends
belong to the closed claim. They neither invoke providers nor perform conversion. Currency validation
checks three uppercase ASCII letters; Conversion owns unit spelling.

Run `composer examples` for [the complete example](examples/consumer.php). Installed consumers can run
`php vendor/kumwe/conversion-extension/examples/consumer.php vendor/autoload.php` from their root.
An explicit unavailable autoload path is refused.

## Contract with Kumwe Core

Providers are declared in signed `contributions.integration.rate_providers` and
`contributions.integration.unit_converters` manifests, then bound by identifier through
`Kumwe\Extension\Spi\Binding\ExtensionBindingRegistrar::moneyRateProvider()` or
`::unitConversionProvider()`. Conversion owns provider interfaces, requests, exact-decimal and unit
semantics. Core owns trusted-generation admission, executable storage, provider selection and lifecycle.

This library supplies no registrar, container provider, factories, rate tables, conversion algorithms,
native binding, fallback or service locator. The withdrawn `MoneyRateProviderRegistrar` and
`UnitConversionProviderRegistrar` must not be recreated. See [architecture](docs/architecture.md) and
[host integration](docs/integration.md).

## API and development

[Public API](docs/public-api.md) documents both types and their invariants. The
[release contract record](docs/release-record.md) preserves symbol mappings, manifest digests, test
ownership and consumer verification requirements.

```bash
composer governance:install
composer install
composer check
```

The full gate validates schemas and rejection cases, source/API/documentation agreement, static analysis,
style, behavior, security, release automation and a fresh archive consumer. Development schema tooling is
excluded from production archives. [Verification](docs/verification.md) explains installed examples,
independent evidence and explicit local dependency overrides for development.

## Releases and license

The version badge tracks Packagist; the CI badge tracks the actual default-branch workflow. The release
workflow reruns the complete gate on the post-rebase commit and publishes or verifies the recorded version.
Existing artifacts are never replaced. Publication and independent verification remain separate from Core
adoption; see [release policy](docs/package-release-standard.md).

Licensed under [Apache-2.0](LICENSE). See [security policy](SECURITY.md).

[version-badge]: https://img.shields.io/packagist/v/kumwe/conversion-extension
[package]: https://packagist.org/packages/kumwe/conversion-extension
[ci-badge]: https://github.com/kumwe/conversion-extension/actions/workflows/ci.yml/badge.svg?branch=main
[ci]: https://github.com/kumwe/conversion-extension/actions/workflows/ci.yml
[php-badge]: https://img.shields.io/packagist/dependency-v/kumwe/conversion-extension/php
[license-badge]: https://img.shields.io/packagist/l/kumwe/conversion-extension
