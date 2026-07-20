# WP Content Contracts

## Getting started

```bash
composer install
composer test    # 39 unit tests: validator, JSON-Schema export/import, response checker
composer lint    # WordPress coding standards (PHPCS)
```

## What is built today

A reusable, framework-free library for defining and validating **versioned content contracts**:

- `FieldType` (`src/FieldType.php`) — typed primitives (string, integer, boolean, url, iso8601,
  string[]) each with its own validation rule.
- `Field` / `Contract` (`src/`) — a named, versioned set of typed fields. `Contract::validate()`
  reports missing required fields, wrong types, and unexpected fields (contracts are closed, not
  just a subset), so producers and consumers can evolve independently behind the version.
- `JsonSchemaExporter` (`src/JsonSchemaExporter.php`) — exports a `Contract` as a JSON Schema
  (draft 2020-12) array or deterministic JSON string. Required fields map to `required`, each
  `FieldType` maps to its JSON Schema type/format, and the object is closed
  (`additionalProperties: false`) to mirror `Contract::validate()`.
- `JsonSchemaImporter` (`src/JsonSchemaImporter.php`) — the exact inverse of the exporter: parses a
  JSON Schema array or string back into a `Contract`, recovering name and version from `$id`, field
  order from `properties`, and requiredness from `required`. Export then import yields an equal
  contract (`Contract::equals()`), so the boundary is symmetric and proven by a round-trip test.
  Malformed schemas fail loudly with `InvalidArgumentException`.
- `ResponseChecker` (`src/ResponseChecker.php`) — framework-free checker for an already-decoded REST
  response, single object or collection. Returns a structured `CheckResult` (`passed` plus located
  `Violation`s) covering missing required fields, wrong types, and unexpected fields, with each
  collection issue tagged by item path (e.g. `/1`).
- `Cli\ResponseCheckCommand` (`src/Cli/ResponseCheckCommand.php`) — registers
  `wp content-contracts check-response <schema-file> <url>`. Its logic-bearing core (decode body,
  run the checker, format the report) is framework-free and unit tested; only the HTTP fetch and
  WP-CLI output binding live behind a `class_exists( '\WP_CLI' )` guard and require a running
  WordPress.

This generalises the content-contract layer proven in the Hybrid Content Delivery Platform.

## Verified against a live install

The WP-CLI command has been run against WordPress 7.0.2, checking a genuinely independent API —
the delivery routes of the `hybrid-wordpress-content-delivery-platform` plugin — against a
contract exported by `JsonSchemaExporter`. It reports conformance and exits 0 for the resource,
and reports ten violations and exits 1 when pointed at the envelope instead. That run also found
three real defects, since fixed: an unreadable or non-contract schema crashed rather than
reporting, a URL answering with HTML did the same, and enveloped responses could not be checked
at all. Command output and details are in
[`docs/RUNTIME-VERIFICATION.md`](docs/RUNTIME-VERIFICATION.md).

## Documented boundary (not yet built)

Authenticated APIs. Every check is currently an unauthenticated `wp_remote_get`, so a contract
for an endpoint behind credentials cannot be checked until the command can pass them through.

> **Document status:** implementation-complete engineering blueprint, not a claim that the software has already been built.

Versioned, testable contracts between WordPress content and external consumers without moving authorization or canonical content rules out of WordPress.

## Who this is for

- headless WordPress teams
- frontend platform engineers
- agencies
- content migration and integration teams

## Required implementation outputs

- WordPress plugin
- Composer/TypeScript packages
- schema and diff CLI
- reference clients
- fixtures
- preview/invalidation security guide
- migration and versioning guide

## Non-negotiable rule

A feature is not complete because code exists. It is complete only when its contract, permissions, failure behavior, automated tests, manual evidence where applicable, documentation, migration/deprecation impact and release artifact are all reviewed.
