# Implementation Backlog

This backlog is the minimum decomposition, not a substitute for issue-specific design. Each issue must include owner, dependencies, security/compatibility/docs impact, test plan and evidence link. Split issues that cannot be reviewed safely in one pull request.

| ID | Work item | Acceptance | Initial status |
|---|---|---|---|
| ISSUE-001 | Bootstrap | Initialize license, governance, CODEOWNERS, security and support files. | Not started |
| ISSUE-002 | Architecture | Accept repository topology, support, schema, environment, trust and test ADRs. | Not started |
| ISSUE-003 | Tooling | Create version files, authoritative lockfiles and immutable installation commands. | Not started |
| ISSUE-004 | Doctor | Implement read-only environment diagnostics and remediation output. | Not started |
| ISSUE-005 | Fixture | Create smallest deterministic known-good fixture and cleanup ownership. | Not started |
| ISSUE-006 | Failure fixture | Create first known-bad fixture and prove the intended gate fails. | Not started |
| ISSUE-007 | Static quality | Configure formatting, lint, types/static analysis, schema and generated-file drift checks. | Not started |
| ISSUE-008 | Integration environment | Create disposable WordPress/database/browser lifecycle with cleanup. | Not started |
| ISSUE-009 | Security | Complete threat model and add permission/input/network/filesystem/redaction tests. | Not started |
| ISSUE-010 | Evidence | Define immutable result/evidence directory, manifest and redaction inspection. | Not started |
| ISSUE-011 | CI | Implement PR target cell using only repository-owned commands. | Not started |
| ISSUE-012 | Scheduled CI | Implement sampled matrix, next-beta checks and maintenance health. | Not started |
| ISSUE-013 | Release | Implement protected tag build, artifact inspection/checksum and artifact-install smoke. | Not started |
| ISSUE-014 | Docs | Verify clean-clone tutorial through an uninvolved reviewer. | Not started |
| ISSUE-015 | Compatibility | Publish dated tested/unsupported matrix tied to release SHA. | Not started |
| ISSUE-016 | Upgrade | Create previous-release fixture and candidate upgrade/recovery test. | Not started |
| ISSUE-017 | CLI: implement `wp content-contracts doctor` | Detect REST, permalinks, registered models, preview and invalidation configuration. Includes unit/contract tests, help text, JSON behavior where applicable, and failure cases. | Not started |
| ISSUE-018 | CLI: implement `wp content-contracts export --contract=<name> --output=<dir>` | Export manifest and schemas deterministically. Includes unit/contract tests, help text, JSON behavior where applicable, and failure cases. | Not started |
| ISSUE-019 | CLI: implement `wp content-contracts validate <dir>` | Validate manifest/schema integrity and server support. Includes unit/contract tests, help text, JSON behavior where applicable, and failure cases. | Not started |
| ISSUE-020 | CLI: implement `wp content-contracts diff <old> <new> --format=<text\|json\|sarif>` | Classify additive, deprecated, breaking and unknown changes. Includes unit/contract tests, help text, JSON behavior where applicable, and failure cases. | Not started |
| ISSUE-021 | CLI: implement `wp content-contracts preview-token --post=<id> --audience=<origin>` | Issue short-lived scoped token only to authorized user. Includes unit/contract tests, help text, JSON behavior where applicable, and failure cases. | Not started |
| ISSUE-022 | CLI: implement `wp content-contracts invalidate --resource=<id> --dry-run` | Show signed event and configured consumers without delivery. Includes unit/contract tests, help text, JSON behavior where applicable, and failure cases. | Not started |
| ISSUE-023 | CLI: implement `tool codegen:ts <contract-dir>` | Generate deterministic types/validators and source hash. Includes unit/contract tests, help text, JSON behavior where applicable, and failure cases. | Not started |
| ISSUE-024 | CLI: implement `tool consumer:test` | Run publish/update/unpublish/delete/redirect/media journeys against reference client. Includes unit/contract tests, help text, JSON behavior where applicable, and failure cases. | Not started |
| ISSUE-025 | Domain: implement `ContractManifest` model | ContractManifest: name/version/models/contexts/deprecations/schema refs/support. Validate serialization, invariants and backward compatibility. | Not started |
| ISSUE-026 | Domain: implement `ModelSchema` model | ModelSchema: fields, types, requiredness, visibility, format and relationships. Validate serialization, invariants and backward compatibility. | Not started |
| ISSUE-027 | Domain: implement `DiffFinding` model | DiffFinding: path, classification, old/new shapes, consumer impact and remediation. Validate serialization, invariants and backward compatibility. | Not started |
| ISSUE-028 | Domain: implement `PreviewGrant` model | PreviewGrant: subject, resource, audience, expiry, nonce/jti and capabilities. Validate serialization, invariants and backward compatibility. | Not started |
| ISSUE-029 | Domain: implement `InvalidationEvent` model | InvalidationEvent: event ID, resource, operation, timestamp, signature version and delivery attempts. Validate serialization, invariants and backward compatibility. | Not started |
| ISSUE-030 | Contract: enforce public API rule | REST remains canonical transport; optional adapters map to the same contract. Add a contract test and documentation link. | Not started |
| ISSUE-031 | Contract: enforce public API rule | Contract export never grants access; context and server permissions remain authoritative. Add a contract test and documentation link. | Not started |
| ISSUE-032 | Contract: enforce public API rule | Generated package exports stable model types, validators and manifest hash. Add a contract test and documentation link. | Not started |
| ISSUE-033 | Contract: enforce public API rule | Invalidation delivery is at-least-once; consumers must be idempotent. Add a contract test and documentation link. | Not started |

## Backlog execution rules

- Complete Bootstrap through Failure fixture before parallel feature expansion.
- Public contracts and schemas require ADR/API-owner review.
- Security-sensitive and release-workflow issues require designated owner review.
- A CLI/model issue is not complete until error and negative paths are tested.
- Documentation follows the real command/artifact; never document a command that has not been run from a clean clone.
- Close an issue only with linked PR, tests and evidence; administrative closure states why it is no longer needed.
