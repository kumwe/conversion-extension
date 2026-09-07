# Conversion Extension

`kumwe/conversion-extension` supplies two immutable contribution definitions under `Kumwe\Conversion\Contribution`: `MoneyRateProviderDefinition` and `UnitConversionProviderDefinition`. They connect Contribution declarations to canonical Conversion requests.

PHP 8.5, `kumwe/contribution` 0.1.1, and `kumwe/conversion` 0.1.3 are the required dependency coordinates. The shared package gate installs these released versions and verifies a fresh archive consumer. A human merge triggers the same checks on the resulting default-branch commit and publishes the recorded version.

```php
use Kumwe\Conversion\Contribution\MoneyRateProviderDefinition;
use Kumwe\Conversion\Contribution\UnitConversionProviderDefinition;

$rates = new MoneyRateProviderDefinition('acme.rates.ecb', ['USD', 'EUR'], 3);
$units = new UnitConversionProviderDefinition('acme.units.trade', ['case', 'unit']);
$manifest = $rates->toArray();
$restored = MoneyRateProviderDefinition::fromArray($manifest);
```

Provider claims contain 1–64 entries, are deduplicated and sorted, and use priorities from -128 through 127. `prices(MoneyConversionRequest)` and `relates(UnitConversionRequest)` return true only when both ends belong to the closed claim. These predicates do not invoke providers or convert values. Currency validation checks three uppercase ASCII letters; it does not consult an ISO currency catalogue. Unit spelling is owned by Conversion.

Run `php examples/consumer.php` to exercise both predicates using actual typed Conversion requests. See [public API](docs/public-api.md), [architecture](docs/architecture.md), [integration](docs/integration.md), [verification](docs/verification.md), and the [migration handoff](MIGRATION-HANDOFF.md).

The former MoneyRateProviderRegistrar and UnitConversionProviderRegistrar classes are withdrawn in the current App/SDK contract. Providers are declared in signed contributions.integration.rate_providers and contributions.integration.unit_converters manifests, then bound by identifier through Kumwe\Extension\Spi\Binding\ExtensionBindingRegistrar::moneyRateProvider() and ::unitConversionProvider(). Conversion owns the corresponding Provider interfaces; App owns trusted-generation admission and executable storage. Do not recreate the withdrawn registrars. This package has no registrar, ConfigProvider, factories, rate tables, conversion algorithms, native extension, PHP fallback, or service locator. “Extension” means a business contribution, not a Zend or PIE extension.

After installing dependencies, run `composer check`. The isolated archive consumer supports explicit local dependency sources for preliminary evidence; App adoption remains a separate integration task after published artifact verification.
