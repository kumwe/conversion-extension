# Verification

The local runtime is PHP 8.5.10. The package gates include PHP syntax, exact reflected public API, source closure and dependency direction, PHPStan at maximum level, PSR-12 source style, behavior tests, executable examples, security audit, and an isolated archive consumer.

The initial package suite exercises 85 assertions. Test input provenance and retained host tests are recorded in `resources/migration/test-ownership.json`. Current behavior tests use real dependency classes, with no stubs, aliases, copied dependency code in src, or host bootstrapping.

`composer clean-consumer` archives the package and installs it into a fresh directory using Composer --no-dev --classmap-authoritative --no-scripts --no-plugins. It checks archive runtime/manifests, excludes development trees, rejects App/SDK classes from the classmap, and executes the installed example. It records archive SHA-256 and consumer evidence outside the repository. A fresh invocation rebuilds the current source.

For dependency development only, set `KUMWE_SOURCE_DEPENDENCIES` to a JSON object mapping package names to `{ "path": "/absolute/source", "satisfies": "0.1.0" }`. Each dependency is mirrored by Composer as `dev-source` with an explicit root alias; the evidence marks this `archive-with-local-source-dependencies` and `release_attestation: false`. No path source is configured in the distributable manifest. Without this variable the tool uses registry dependencies. Local source aliases test composition but do not prove immutable release identity, registry availability or independent dependency attestation.

The required release pipeline, supported-platform CI matrix, external security audit and final published artifact verification must all be established before review-ready/publication. The package remains draft and publication-blocked; see the handoff for exact pending gates.
