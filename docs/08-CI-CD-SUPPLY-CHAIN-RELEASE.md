# CI/CD, Supply Chain and Release

## Pull-request workflow

1. Validate changed files, generated-file freshness and repository policies.
2. Install from lockfiles with immutable/frozen mode.
3. Run formatting/lint/types/schemas/static analysis.
4. Run unit and contract suites.
5. Start disposable integration environment and target compatibility cell.
6. Run E2E smoke, security checks and relevant budgets.
7. Upload bounded evidence; publish a concise PR summary.
8. Cancel stale runs safely without leaving shared state.

## Scheduled workflow

Run full or sampled compatibility matrix, next WordPress/WooCommerce beta/RC where relevant, dependency/security scans, fixture/resource availability, long-running E2E and maintenance-health checks. Scheduled failures create an issue only after deduplication and with an owner/routing policy.

## Release workflow

- Releases originate from an annotated/protected tag matching the documented version.
- Release workflow checks the tag commit is on the protected default branch.
- Re-run mandatory quality, security, compatibility, upgrade and artifact-install tests.
- Build from a clean checkout using lockfiles; do not upload a developer workstation ZIP.
- Inspect archive allowlist/denylist, normalize timestamps if reproducibility is supported, calculate checksums and produce provenance/attestation where practical.
- Generate human changelog from reviewed entries and include deprecations/migrations/known issues.
- Publish packages/artifacts only from a protected environment with minimum permissions.
- Verify installation from the published artifact, not merely the build directory.

## Supply-chain controls

- Pin third-party GitHub Actions to immutable commit SHAs; review updates.
- Limit `GITHUB_TOKEN` permissions per job and avoid `pull_request_target` execution of untrusted code.
- Use dependency lockfiles and verify package registries/names.
- Run secret scanning, dependency review and code scanning where supported.
- Protect release tags/environments; require maintainer 2FA and recovery planning.
- Record SBOM/provenance when tools and registries support it without false guarantees.

## Release criteria

- WordPress plugin
- Composer/TypeScript packages
- schema and diff CLI
- reference clients
- fixtures
- preview/invalidation security guide
- migration and versioning guide

No release with known critical/high security issue, data-loss risk, broken required compatibility cell, undocumented breaking change, failing upgrade, missing license attribution or unreproducible release source. Medium/low known issues require explicit owner, workaround and release-note disclosure.
