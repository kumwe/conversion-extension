# Architecture

The former MoneyRateProviderRegistrar and UnitConversionProviderRegistrar classes are withdrawn in the current App/SDK contract. Providers are declared in signed contributions.integration.rate_providers and contributions.integration.unit_converters manifests, then bound by identifier through Kumwe\Extension\Spi\Binding\ExtensionBindingRegistrar::moneyRateProvider() and ::unitConversionProvider(). Conversion owns the corresponding Provider interfaces; App owns trusted-generation admission and executable storage. Do not recreate the withdrawn registrars.

MoneyRateProviderDefinition and UnitConversionProviderDefinition implement the canonical ContributionDefinition interface and accept canonical Conversion request types in their eligibility predicates. Conversion remains the owner of request, exact-decimal, rounding, and unit spelling semantics. Neither definition stores providers, selects providers, admits trust, or activates extensions.

The two immutable provider definitions are this package’s complete current responsibility. Runtime binding uses the existing SDK SPI; this library does not create a second registry or provider container. Service/provider lists remain empty because these values have no injected runtime collaborators.

The production token guard permits only the documented dependency namespaces and rejects host/native/container loading. The source map records exact source commits, paths, source digests, new symbols, target paths, and current target digests. Consumer inventory preserves source provenance and known host integration paths.
