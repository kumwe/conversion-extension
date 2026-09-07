<?php

declare(strict_types=1);

use Kumwe\Contribution\ContributionDefinition;
use Kumwe\Conversion\Contribution\MoneyRateProviderDefinition;
use Kumwe\Conversion\Contribution\UnitConversionProviderDefinition;
use Kumwe\Conversion\Contract\MoneyConversionRequest;
use Kumwe\Conversion\Contract\UnitConversionRequest;
use Kumwe\Conversion\Decimal\ExactDecimal;
use Kumwe\Conversion\Value\MoneyRoundingMode;
use Kumwe\Conversion\Value\MoneyValue;
use Kumwe\Conversion\Value\QuantityRoundingMode;
use Kumwe\Conversion\Value\QuantityValue;

require dirname(__DIR__) . '/vendor/autoload.php';
$count = 0;
function same(mixed $expected, mixed $actual): void {
    global $count; ++$count;
    if ($actual !== $expected) { throw new RuntimeException('Expected ' . var_export($expected, true) . ', received ' . var_export($actual, true)); }
}
function refuses(callable $operation): void {
    global $count; ++$count;
    try { $operation(); } catch (InvalidArgumentException) { return; }
    throw new RuntimeException('Invalid declaration was accepted.');
}
foreach ([MoneyRateProviderDefinition::class => ['currencies', ['USD', 'EUR'], ['EUR', 'USD']], UnitConversionProviderDefinition::class => ['units', ['unit', 'case'], ['case', 'unit']]] as $class => [$key, $values, $sorted]) {
    $definition = new $class('acme.provider.main', [...$values, $values[0]], 3);
    same(true, $definition instanceof ContributionDefinition);
    same('acme.provider.main', $definition->identifier()); same(3, $definition->priority()); same($sorted, $definition->$key);
    $document = ['provider_id' => 'acme.provider.main', $key => $sorted, 'priority' => 3];
    same($document, $definition->toArray()); same($document, $class::fromArray($document)->toArray());
    foreach (['trade', 'Acme.Units.Trade', '.units.trade', 'acme..trade', '9acme.units', "acme.name\n"] as $identifier) { refuses(fn () => new $class($identifier, $values)); }
    refuses(fn () => new $class('acme.provider.main', []));
    refuses(fn () => new $class('acme.provider.main', array_fill(0, 65, $values[0])));
    same([$values[0]], (new $class('acme.provider.main', array_fill(0, 64, $values[0])))->$key);
    foreach ([-129, 128, 1000] as $priority) { refuses(fn () => new $class('acme.provider.main', $values, $priority)); }
    same(-128, (new $class('acme.provider.main', $values, -128))->priority());
    same(127, (new $class('acme.provider.main', $values, 127))->priority());
    foreach (array_keys($document) as $member) { $invalid = $document; unset($invalid[$member]); refuses(fn () => $class::fromArray($invalid)); }
    refuses(fn () => $class::fromArray($document + ['extra' => true]));
    foreach (['provider_id' => 12, $key => 'not-a-list', 'priority' => '0'] as $member => $value) { $invalid = $document; $invalid[$member] = $value; refuses(fn () => $class::fromArray($invalid)); }
    foreach ([['name' => $values[0]], [$values[0], 12], [[$values[0]]], [null], [new stdClass()]] as $invalidValues) { $invalid = $document; $invalid[$key] = $invalidValues; refuses(fn () => $class::fromArray($invalid)); }
    foreach ([['name' => $values[0]], [12], [null], [new stdClass()], [[$values[0]]]] as $invalidValues) { refuses(fn () => new $class('acme.provider.main', $invalidValues)); }
}
foreach (['usd', 'US', 'USDD', '', "USD\n"] as $currency) { refuses(fn () => new MoneyRateProviderDefinition('acme.rates', [$currency])); }
foreach (['metric tonne', '', '-case', 'case!', str_repeat('u', 64)] as $unit) { refuses(fn () => new UnitConversionProviderDefinition('acme.units', [$unit])); }
same([str_repeat('u', 63)], (new UnitConversionProviderDefinition('acme.units', [str_repeat('u', 63)]))->units);
$amount = ExactDecimal::fromString('2.000000', 12, 6); $instant = new DateTimeImmutable('2026-08-14T00:00:00Z');
$money = new MoneyRateProviderDefinition('acme.rates', ['USD', 'EUR']);
foreach ([['USD', 'EUR', true], ['EUR', 'USD', true], ['USD', 'GBP', false], ['GBP', 'EUR', false], ['GBP', 'JPY', false]] as [$source, $target, $eligible]) {
    $request = new MoneyConversionRequest(new MoneyValue($amount, $source), $target, $instant, 12, 6, MoneyRoundingMode::HalfUp);
    same($eligible, $money->prices($request));
}
$units = new UnitConversionProviderDefinition('acme.units', ['case', 'unit']);
foreach ([['case', 'unit', true], ['unit', 'case', true], ['case', 'pallet', false], ['pallet', 'unit', false], ['pallet', 'layer', false]] as [$source, $target, $eligible]) {
    $request = new UnitConversionRequest(new QuantityValue($amount, $source), $target, $instant, 12, 6, QuantityRoundingMode::HalfUp);
    same($eligible, $units->relates($request));
}
echo "$count behavior assertions passed.\n";
