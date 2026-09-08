<?php

declare(strict_types=1);

use Kumwe\Conversion\Contribution\MoneyRateProviderDefinition;
use Kumwe\Conversion\Contribution\UnitConversionProviderDefinition;
use Kumwe\Conversion\Contract\MoneyConversionRequest;
use Kumwe\Conversion\Contract\UnitConversionRequest;
use Kumwe\Conversion\Decimal\ExactDecimal;
use Kumwe\Conversion\Value\MoneyRoundingMode;
use Kumwe\Conversion\Value\MoneyValue;
use Kumwe\Conversion\Value\QuantityRoundingMode;
use Kumwe\Conversion\Value\QuantityValue;

$autoload = $argv[1] ?? dirname(__DIR__) . '/vendor/autoload.php';
if (isset($argv[1]) || !class_exists(MoneyRateProviderDefinition::class)) {
    if (!is_file($autoload) || !is_readable($autoload)) {
        throw new RuntimeException('Composer autoload file is missing or unreadable: ' . $autoload);
    }
    require_once $autoload;
}
$money = new MoneyRateProviderDefinition('acme.rates.ecb', ['USD', 'EUR', 'USD'], 3);
$units = new UnitConversionProviderDefinition('acme.units.trade', ['unit', 'case'], -2);
$amount = ExactDecimal::fromString('2.00', 12, 2);
$instant = new DateTimeImmutable('2026-08-14T00:00:00Z');
if (!$money->prices(new MoneyConversionRequest(new MoneyValue($amount, 'USD'), 'EUR', $instant, 12, 2, MoneyRoundingMode::HalfUp))) { throw new RuntimeException('Declared pair was refused.'); }
if (!$units->relates(new UnitConversionRequest(new QuantityValue($amount, 'case'), 'unit', $instant, 12, 2, QuantityRoundingMode::HalfUp))) { throw new RuntimeException('Declared unit pair was refused.'); }
if ($money->currencies !== ['EUR', 'USD'] || $units->units !== ['case', 'unit']) { throw new RuntimeException('Canonical claim changed.'); }
if (MoneyRateProviderDefinition::fromArray($money->toArray())->toArray() !== $money->toArray() || UnitConversionProviderDefinition::fromArray($units->toArray())->toArray() !== $units->toArray()) { throw new RuntimeException('Manifest round trip failed.'); }
echo "Money and unit provider declarations matched real conversion requests.\n";
