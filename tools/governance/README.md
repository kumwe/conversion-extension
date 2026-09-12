# Authoritative governance validation

`composer governance:install` installs exact locked development dependencies. `composer governance` runs full
Draft 2020-12 JSON Schema validation of the public API, capabilities, service map and package release record, then checks
source, generated documentation, symbol ownership and manifest digests. Both `composer check` and the hosted source and
archive gates require it. This development tooling and its dependencies are excluded from production archives.

The three public manifest schema snapshots come from `kumwe/app` at
`55bd9d22ed8846e5ad88b49fb77117a09f93fb76`, under `docs/architecture/governance/schemas/`.
The package release record uses the coordinated Core and Extension SDK `kumwe-package-release-record/v1` schema.
These are validation inputs, not package-owned schema forks. Review authoritative upstream changes before updating them.

`test.cjs` validates a real positive document set and independently mutates in-memory copies. Its refusals cover malformed
schema shapes, obsolete workflow state, false Core completion claims, and missing, duplicate or stale ownership evidence.
It never rewrites production artifacts to run a negative fixture.

For an intentional public source change, run `composer api:record` and `composer docs:record`. The API command also
regenerates capability and service documents from reviewed source. Update the release record's symbol mappings and SHA-256
identities before running the complete gate. Confirm publication using immutable external release evidence.
