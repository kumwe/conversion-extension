# Public API

All symbols below are exported by `kumwe/conversion-extension`. The machine-readable signature and enum/constant/property inventory is `resources/public-api/v1.json`; `composer api` rejects drift. Parameter names are part of the documented PHP API.

These values and operations perform no I/O, trust checks, authorization, provider selection, persistence, transaction management, or cross-process coordination. Instances use readonly state except the diagnostic exception inherited from PHP. Returned arrays are values, not shared registry state. There is no global mutable registry. Host code owns concurrency, transactions, rendering, and active contribution lifecycles. Invalid argument and conformance exceptions leave no external state changes. Serialization produces deterministic arrays, not encoded JSON, decimal computation, or cryptographic bytes. Source `@since 2.0.0` annotations identify the App API lineage; they do not announce a package release.

## `Kumwe\Conversion\Contribution\MoneyRateProviderDefinition`

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

Public readonly `array $currencies`. Declared ISO 4217 codes, deduplicated and sorted so two orderings declare the same thing.

@var    list<string>
@since  2.0.0

### `__construct(string $providerId, array $currencies, int $priority = 0)`

Declare one rate provider, the currencies it prices, and where it sits in resolution order.


- `@param   string        $providerId  Namespaced identifier inside the declaring package's namespace.`
- `@param   list<string>  $currencies  Uppercase ISO 4217 codes this provider is prepared to price between.`
- `@param   int           $priority    Resolution order, lowest first, between -128 and 127. `
- `@throws  InvalidArgumentException  When the identifier is not namespaced, the currency list is empty,          over its bound or holds something other than an ISO 4217 code, or the priority is outside          its range. `
- `@since   2.0.0`

### `identifier(): string`

The identifier this provider is registered, resolved, and attributed under.


- `@return  string  Namespaced provider identity, matching the `provider` on every rate it supplies. `
- `@since   2.0.0`

### `priority(): int`

Where this provider sits when more than one package can price the same pair.


- `@return  int  Lowest first; equal priorities resolve in identifier order. `
- `@since   2.0.0`

### `prices(Kumwe\Conversion\Contract\MoneyConversionRequest $request): bool`

Whether this declaration admits a conversion at all, before the provider itself is consulted.


- `@param   MoneyConversionRequest  $request  Conversion a caller is looking for a rate for. `
- `@return  bool  True only when both the stored and the target currency are declared. `
- `@since   2.0.0`

### `toArray(): array`

Serialize the declaration for the signed manifest, the runtime publication, and inventory.


- `@return  array{provider_id: string, currencies: list<string>, priority: int}  Canonical declaration. `
- `@since   2.0.0`

### `static fromArray(array $data): Kumwe\Conversion\Contribution\MoneyRateProviderDefinition`

Reconstitute the declaration from validated manifest data.


- `@param   array<string, mixed>  $data  Declaration as `toArray()` produced it. `
- `@return  self  Validated rate-provider declaration. `
- `@throws  InvalidArgumentException  When a member is missing, extra, or mistyped. `
- `@since   2.0.0`

## `Kumwe\Conversion\Contribution\UnitConversionProviderDefinition`

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

Public readonly `array $units`. Declared unit identifiers, deduplicated and sorted so two orderings declare the same thing.

@var    list<string>
@since  2.0.0

### `__construct(string $providerId, array $units, int $priority = 0)`

Declare one conversion provider, the units it relates, and where it sits in resolution order.


- `@param   string        $providerId  Namespaced identifier inside the declaring package's namespace.`
- `@param   list<string>  $units       Portable unit identifiers this provider is prepared to convert between.`
- `@param   int           $priority    Resolution order, lowest first, between -128 and 127. `
- `@throws  InvalidArgumentException  When the identifier is not namespaced, the unit list is empty, over          its bound or holds something other than a portable unit identifier, or the priority is          outside its range. `
- `@since   2.0.0`

### `identifier(): string`

The identifier this provider is registered, resolved, and attributed under.


- `@return  string  Namespaced provider identity, matching the `provider` on every factor it supplies. `
- `@since   2.0.0`

### `priority(): int`

Where this provider sits when more than one package can relate the same pair of units.


- `@return  int  Lowest first; equal priorities resolve in identifier order. `
- `@since   2.0.0`

### `relates(Kumwe\Conversion\Contract\UnitConversionRequest $request): bool`

Whether this declaration admits a conversion at all, before the provider itself is consulted.


- `@param   UnitConversionRequest  $request  Conversion a caller is looking for a factor for. `
- `@return  bool  True only when both the stored and the target unit are declared. `
- `@since   2.0.0`

### `toArray(): array`

Serialize the declaration for the signed manifest, the runtime publication, and inventory.


- `@return  array{provider_id: string, units: list<string>, priority: int}  Canonical declaration. `
- `@since   2.0.0`

### `static fromArray(array $data): Kumwe\Conversion\Contribution\UnitConversionProviderDefinition`

Reconstitute the declaration from validated manifest data.


- `@param   array<string, mixed>  $data  Declaration as `toArray()` produced it. `
- `@return  self  Validated unit-conversion declaration. `
- `@throws  InvalidArgumentException  When a member is missing, extra, or mistyped. `
- `@since   2.0.0`

