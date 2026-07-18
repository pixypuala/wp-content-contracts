# Technical Contracts, Commands and Schemas

This chapter removes ambiguity before code. Names may change only through an ADR; the implemented README and CLI help must remain synchronized with the accepted contract.

## Required command surface

| Command | Required behavior |
|---|---|
| wp content-contracts doctor | Detect REST, permalinks, registered models, preview and invalidation configuration. |
| wp content-contracts export --contract=<name> --output=<dir> | Export manifest and schemas deterministically. |
| wp content-contracts validate <dir> | Validate manifest/schema integrity and server support. |
| wp content-contracts diff <old> <new> --format=<text\|json\|sarif> | Classify additive, deprecated, breaking and unknown changes. |
| wp content-contracts preview-token --post=<id> --audience=<origin> | Issue short-lived scoped token only to authorized user. |
| wp content-contracts invalidate --resource=<id> --dry-run | Show signed event and configured consumers without delivery. |
| tool codegen:ts <contract-dir> | Generate deterministic types/validators and source hash. |
| tool consumer:test | Run publish/update/unpublish/delete/redirect/media journeys against reference client. |

## Configuration example

```yaml
manifestVersion: 1
name: editorial-content
version: 0.1.0
models:
  - postType: article
    contexts: [view, edit]
    fields: [id, slug, title, content, modified, featured_media]
preview:
  audience: https://frontend.example.test
  ttlSeconds: 120
invalidation:
  signatureVersion: v1
  replayWindowSeconds: 300
```

The final implementation must publish a machine-readable JSON Schema, reject unknown/unsafe fields according to policy, report source locations for invalid input, and support `--format=json` for automation where appropriate. Environment variables may provide secrets or CI overrides but cannot silently replace committed project behavior.

## Core data models

- ContractManifest: name/version/models/contexts/deprecations/schema refs/support.
- ModelSchema: fields, types, requiredness, visibility, format and relationships.
- DiffFinding: path, classification, old/new shapes, consumer impact and remediation.
- PreviewGrant: subject, resource, audience, expiry, nonce/jti and capabilities.
- InvalidationEvent: event ID, resource, operation, timestamp, signature version and delivery attempts.

## API and stability rules

- REST remains canonical transport; optional adapters map to the same contract.
- Contract export never grants access; context and server permissions remain authoritative.
- Generated package exports stable model types, validators and manifest hash.
- Invalidation delivery is at-least-once; consumers must be idempotent.

## Common exit-code contract

| Code | Meaning | Retry guidance |
|---|---|---|
| 0 | All requested operations completed and required assertions passed | No retry needed |
| 1 | Valid execution found a contract/budget/audit/test failure | Fix product/configuration; blind retry prohibited |
| 2 | Invalid command or configuration | Correct input |
| 3 | Unsupported or missing environment/dependency | Change environment or support policy |
| 4 | Permission or safety policy denied the operation | Do not bypass; obtain correct authorization/environment |
| 5 | Setup, migration or fixture preparation failed | Inspect diagnostics; clean owned state before retry |
| 6 | Timeout, cancellation or external/network failure | Retry only under documented bounded policy |
| 7 | Infrastructure failure unrelated to evaluated product behavior | Retry after environment repair; preserve original evidence |
| 8 | Internal defect/invariant violation | File a bug with redacted diagnostic bundle |

Commands that do not need all codes may use the applicable subset, but meanings cannot conflict.

## Output and logging contract

- Human output goes to stdout; diagnostics/progress to stderr where CLI conventions require machine-readable stdout.
- `--format=json` emits one valid documented schema, no decorative prose.
- Every run prints or records run ID, tool version, source SHA, platform versions, config hash and safety mode.
- Errors contain stable code, path/subject, remediation and redacted context.
- Verbose/debug mode is opt-in and still redacts secrets and personal data.
- Cancellation returns a distinct status and runs ownership-based cleanup.

## Schema evolution

Schemas include `schemaVersion`. Additive optional fields may be backward compatible; required fields, changed meaning/type, renamed IDs and removed enum values are breaking. Readers must reject unsupported major versions clearly. Golden fixtures for every supported schema version remain in tests through the deprecation window.
