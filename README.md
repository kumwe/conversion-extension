# Conversion Extension

`kumwe/conversion-extension` supplies two immutable contribution definitions under `Kumwe\Conversion\Contribution`: `MoneyRateProviderDefinition` and `UnitConversionProviderDefinition`. They connect Contribution declarations to canonical Conversion requests.

PHP 8.5, `kumwe/contribution` 0.1.0, and `kumwe/conversion` 0.1.2 are the exact intended dependency coordinates. Publication requires independently verified immutable dependency evidence; a local source consumer alone does not provide that evidence.

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

The two App registrars retain executable provider storage, trusted lifecycle activation, and host authority. This package has no registrar, ConfigProvider, factories, rate tables, conversion algorithms, native extension, PHP fallback, or service locator. “Extension” means a business contribution, not a Zend or PIE extension.

After installing dependencies, run `composer check`. The isolated archive consumer supports explicit local dependency sources for preliminary evidence; publication and App adoption still require separate release verification.
