# Security and compatibility

Report sensitive defects through the repository GitHub private vulnerability reporting channel. Do not publish exploit payloads in public issues.

PHP 8.5 is the current supported runtime declaration. Stable public signatures, enum values and serialized declaration keys are tracked by the API manifest. Pre-1.0 dependency requirements are exact pins. Backward-incompatible grammar, ordering, limits or exception changes require a deliberate versioned compatibility decision and tests. Source @since annotations record origin and are not release declarations.

The package contains no I/O, cryptographic implementation, authorization enforcement, provider execution or persistence. Hosts must preserve their own trust, lifecycle and authorization boundaries. Immutable releases and independent attestation are required before dependent publication or App adoption.
