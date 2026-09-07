# Architecture

2 source types extracted; planning count was 4. The two registrar types remain App-owned because their closure stores executable providers and requires trusted active contribution authority.

MoneyRateProviderDefinition and UnitConversionProviderDefinition implement the canonical ContributionDefinition interface and accept canonical Conversion request types in their eligibility predicates. Conversion remains the owner of request, exact-decimal, rounding, and unit spelling semantics. Neither definition stores providers, selects providers, admits trust, or activates extensions.

The candidate brief expected registrar extraction and DI providers. Refreshed source closure shows both registrars require App-owned registries/trusted activation. They remain host-owned; inventing an empty provider would misrepresent the extracted runtime. Service and provider lists are intentionally empty and checked by the architecture gate.

The production token guard permits only the documented dependency namespaces and rejects host/native/container loading. The source map records exact source commits, paths, source digests, new symbols, target paths, and current target digests. Consumer inventory records file-level migration references without modifying App.
