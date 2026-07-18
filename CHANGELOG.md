# Changelog

All notable changes to this project are documented here. The format is based on
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and this project adheres
to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Repository scaffolding: governance files, docs, and CI skeleton.
- FieldType, Field, and Contract: define and validate versioned content contracts (typed, closed).
- JsonSchemaExporter: export a Contract as a JSON Schema (draft 2020-12) array or deterministic
  JSON string; required fields map to `required`, types map to JSON Schema types/formats, and the
  object is closed (`additionalProperties: false`).
- JsonSchemaImporter: parse a JSON Schema array or string back into a Contract (the exact inverse of
  the exporter); recovers name/version from `$id`, field order from `properties`, and requiredness
  from `required`. Malformed schemas raise `InvalidArgumentException`.
- Contract::equals / Field::equals: structural equality, proving export/import round-trip symmetry.
- ResponseChecker + CheckResult + Violation: framework-free check of a decoded REST response (single
  object or collection) against a Contract, returning a structured pass/violations result with each
  issue located by path.
- Cli\ResponseCheckCommand: WP-CLI `content-contracts check-response` with a framework-free, unit-
  tested core and thin `WP_CLI`-guarded glue; the live REST fetch requires a running WordPress.
- 39 PHPUnit tests; PHPCS/WPCS clean; CI on PHP 8.1 and 8.3.
