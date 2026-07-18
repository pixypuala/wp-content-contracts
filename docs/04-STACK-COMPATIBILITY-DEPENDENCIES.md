# Stack, Compatibility and Dependency Policy

## Compatibility targets

- WordPress current/previous
- PHP project policy versions
- Node active LTS versions used by codegen examples
- single and multisite where contracts differ
- pretty permalink variants
- authenticated and anonymous REST contexts
- optional GraphQL adapter tested separately

## Policy, not frozen guesses

Do not promise support through a calendar year by naming versions that do not exist. Publish:

- a minimum version with a reason;
- current stable support;
- previous supported release where maintenance cost is justified;
- scheduled non-blocking tests against the next beta/RC;
- an end-of-support rule and announcement period.

The compatibility table is reviewed for every minor release and at least quarterly. A green untested cell is prohibited; use `tested`, `expected`, `experimental`, `unsupported`, or `unknown`.

## Runtime/tool separation

- Composer is authoritative for PHP dependency resolution and autoloading.
- A single declared JavaScript package manager is authoritative; do not commit competing lockfiles.
- Browser automation uses a supported Playwright/Node boundary even when another JS runtime drives general scripts.
- WordPress/WooCommerce/plugin fixtures are version-pinned in CI plans and reports.
- Development tools do not ship in production archives unless required at runtime.

## Dependency admission checklist

A dependency needs a documented owner, purpose, license, maintenance health, security history, transitive impact, bundle/runtime cost, supported versions, replacement strategy and test coverage. Prefer a small internal implementation only when the behavior is well-bounded and cheaper to maintain than the dependency.

## Update workflow

1. Automated update PR opens with changelog and dependency diff.
2. Lockfile integrity and license/security scans run.
3. Unit/contract/integration and sampled E2E run.
4. Breaking/deprecated APIs are searched and reviewed.
5. Generated artifacts are recreated and diffed.
6. Maintainer records risk and rollback path.
7. Merge only after required compatibility cells pass.

## Unsupported environment behavior

Fail early with detected versions and the supported policy. Never continue silently when an unsupported version can corrupt data, invalidate results or weaken permissions. Experimental opt-in must be clearly labeled and excluded from formal compatibility claims.
