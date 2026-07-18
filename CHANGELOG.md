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
- 16 PHPUnit tests; PHPCS/WPCS clean; CI on PHP 8.1 and 8.3.
