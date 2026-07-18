# Security, Privacy and Threat Model

## Project-specific controls

- Server-side authorization is canonical; generated clients never infer access from UI state.
- Preview tokens are short-lived, scoped, audience-bound, single-purpose and excluded from logs/referrers.
- Webhook/invalidation signatures use constant-time verification, rotation and replay windows.
- REST projections whitelist fields and prevent accidental private-meta exposure.
- Remote media and URL handling includes SSRF-safe validation guidance.

## Baseline trust boundaries

| Boundary | Primary risk | Required control/evidence |
|---|---|---|
| Untrusted user/input | Validation bypass, injection, unsafe output | Schema validation, sanitization, escaping, prepared APIs, negative tests |
| Authenticated low-privilege user | Capability bypass, private data access | Explicit authorization, role matrix, least-disclosure responses |
| Remote service/network | SSRF, spoofed webhook, replay, timeout | Allowlist, signatures, replay defense, timeouts, retry/idempotency policy |
| Filesystem/archive | Traversal, overwrite, secret inclusion | Safe temp roots, path normalization, size limits, artifact inspection |
| CI/pull request | Secret theft, workflow injection, poisoned dependency | Least permissions, pinned actions, trusted release context, lockfiles |
| Maintainer/release | Account compromise, malicious artifact | 2FA, protected tags/environments, provenance, two-person review |

## Secure development rules

1. Validate type, format, range, ownership and state at the boundary.
2. Authorize immediately before the protected action; do not trust client-side visibility or a previous check.
3. Encode/escape for the output context; never rely on input sanitization as output safety.
4. Use platform database/filesystem/HTTP APIs safely; prohibit string-built SQL and unchecked remote destinations.
5. Treat logs, screenshots, traces and reports as potential sensitive-data stores.
6. Apply timeouts, cancellation and bounded retries to every external or long-running operation.
7. Make state-changing handlers idempotent where duplicate delivery/submission is possible.
8. Protect migrations and destructive commands with backups, confirmation, scope and dry-run where feasible.
9. Never run untrusted pull-request code with release or repository-write secrets.
10. Document residual risks and unsupported threat scenarios.

## Security verification

- Static analysis at the strictest practical project level with a reviewed baseline of zero ignored new findings.
- Dependency vulnerability and license checks on pull requests, schedule and release.
- Secret scanning and high-entropy fixture review.
- Unit/integration tests for authorization, validation, redaction and dangerous boundaries.
- Manual threat-model review before public beta and v1.0.
- Release artifact inspection for secrets, dev files, source maps and unexpected executables.

## Vulnerability handling

Private reports receive acknowledgement, triage, severity/affected-version assessment, fix coordination, regression tests, release/advisory and credit according to `SECURITY.md`. Never require a reporter to publish exploit details before a fix. Do not promise response times the maintainer team cannot sustain.
