# Public API

Generated from package source. The contracts below retain source PHPDoc, complete callable signatures, public properties and constants. The canonical manifest is `resources/public-api/v1.json`. Run `composer docs` to reject drift. Host adapters retain provider admission, persistence, authorization and transactions.

## Kumwe\Conversion\Contribution\MoneyRateProviderDefinition

Source: [src/MoneyRateProviderDefinition.php](../src/MoneyRateProviderDefinition.php).

```text
What a package declares before any of its code is allowed to supply an exchange rate.

Core ships no rate table, no rate feed and no rate policy, so every rate in a Kumwe installation
arrives from a package that said in its signed manifest that it would supply one. The declaration is
what makes that inspectable before install: an operator can read which currencies a package claims to
price and where it sits in the resolution order, without running it. An external rate service, a
manually administered table, a bank feed and a contractual fixed rate all declare through this same
shape.

The currency list is a closed claim, not a hint. A conversion whose source or target currency is
outside it is not offered to that provider at all, so a package cannot quietly widen its reach after
admission by changing its runtime behaviour.

@since  2.0.0
```

### `MAXIMUM_CURRENCIES`

Constant value: `64`.

### `$currencies`

```php
public readonly array $currencies;
```

```text
Declared ISO 4217 codes, deduplicated and sorted so two orderings declare the same thing.

@var    list<string>
@since  2.0.0
```

### `__construct`

```php
public function __construct(string $providerId, array $currencies, int $priority = 0)
```

```text
Declare one rate provider, the currencies it prices, and where it sits in resolution order.

@param   string        $providerId  Namespaced identifier inside the declaring package's namespace.
@param   list<string>  $currencies  Uppercase ISO 4217 codes this provider is prepared to price between.
@param   int           $priority    Resolution order, lowest first, between -128 and 127.

@throws  InvalidArgumentException  When the identifier is not namespaced, the currency list is empty,
         over its bound or holds something other than an ISO 4217 code, or the priority is outside
         its range.

@since   2.0.0
```

### `identifier`

```php
public function identifier(): string
```

```text
The identifier this provider is registered, resolved, and attributed under.

@return  string  Namespaced provider identity, matching the `provider` on every rate it supplies.

@since   2.0.0
```

### `priority`

```php
public function priority(): int
```

```text
Where this provider sits when more than one package can price the same pair.

@return  int  Lowest first; equal priorities resolve in identifier order.

@since   2.0.0
```

### `prices`

```php
public function prices(Kumwe\Conversion\Contract\MoneyConversionRequest $request): bool
```

```text
Whether this declaration admits a conversion at all, before the provider itself is consulted.

@param   MoneyConversionRequest  $request  Conversion a caller is looking for a rate for.

@return  bool  True only when both the stored and the target currency are declared.

@since   2.0.0
```

### `toArray`

```php
public function toArray(): array
```

```text
Serialize the declaration for the signed manifest, the runtime publication, and inventory.

@return  array{provider_id: string, currencies: list<string>, priority: int}  Canonical declaration.

@since   2.0.0
```

### `fromArray`

```php
public static function fromArray(array $data): Kumwe\Conversion\Contribution\MoneyRateProviderDefinition
```

```text
Reconstitute the declaration from validated manifest data.

@param   array<string, mixed>  $data  Declaration as `toArray()` produced it.

@return  self  Validated rate-provider declaration.

@throws  InvalidArgumentException  When a member is missing, extra, or mistyped.

@since   2.0.0
```

## Kumwe\Conversion\Contribution\UnitConversionProviderDefinition

Source: [src/UnitConversionProviderDefinition.php](../src/UnitConversionProviderDefinition.php).

```text
What a package declares before any of its code is allowed to convert between units of measure.

Core ships no conversion table, no unit standard and no packaging policy, so every factor in a Kumwe
installation arrives from a package that said in its signed manifest that it would supply one. The
declaration is what makes that inspectable before install: an operator can read which units a package
claims to relate and where it sits in the resolution order, without running it. A metric standards
table, a trade-unit table administered by hand and a supplier's contractual case size all declare
through this same shape.

The unit list is a closed claim, not a hint. A conversion whose source or target unit is outside it is
not offered to that provider at all, so a package cannot quietly widen its reach after admission by
changing its runtime behaviour.

@since  2.0.0
```

### `MAXIMUM_UNITS`

Constant value: `64`.

### `$units`

```php
public readonly array $units;
```

```text
Declared unit identifiers, deduplicated and sorted so two orderings declare the same thing.

@var    list<string>
@since  2.0.0
```

### `__construct`

```php
public function __construct(string $providerId, array $units, int $priority = 0)
```

```text
Declare one conversion provider, the units it relates, and where it sits in resolution order.

@param   string        $providerId  Namespaced identifier inside the declaring package's namespace.
@param   list<string>  $units       Portable unit identifiers this provider is prepared to convert between.
@param   int           $priority    Resolution order, lowest first, between -128 and 127.

@throws  InvalidArgumentException  When the identifier is not namespaced, the unit list is empty, over
         its bound or holds something other than a portable unit identifier, or the priority is
         outside its range.

@since   2.0.0
```

### `identifier`

```php
public function identifier(): string
```

```text
The identifier this provider is registered, resolved, and attributed under.

@return  string  Namespaced provider identity, matching the `provider` on every factor it supplies.

@since   2.0.0
```

### `priority`

```php
public function priority(): int
```

```text
Where this provider sits when more than one package can relate the same pair of units.

@return  int  Lowest first; equal priorities resolve in identifier order.

@since   2.0.0
```

### `relates`

```php
public function relates(Kumwe\Conversion\Contract\UnitConversionRequest $request): bool
```

```text
Whether this declaration admits a conversion at all, before the provider itself is consulted.

@param   UnitConversionRequest  $request  Conversion a caller is looking for a factor for.

@return  bool  True only when both the stored and the target unit are declared.

@since   2.0.0
```

### `toArray`

```php
public function toArray(): array
```

```text
Serialize the declaration for the signed manifest, the runtime publication, and inventory.

@return  array{provider_id: string, units: list<string>, priority: int}  Canonical declaration.

@since   2.0.0
```

### `fromArray`

```php
public static function fromArray(array $data): Kumwe\Conversion\Contribution\UnitConversionProviderDefinition
```

```text
Reconstitute the declaration from validated manifest data.

@param   array<string, mixed>  $data  Declaration as `toArray()` produced it.

@return  self  Validated unit-conversion declaration.

@throws  InvalidArgumentException  When a member is missing, extra, or mistyped.

@since   2.0.0
```

