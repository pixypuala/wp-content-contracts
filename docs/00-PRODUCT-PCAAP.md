# Product Definition Using PCAAP

## Problem

Headless and hybrid builds often duplicate content assumptions in PHP and TypeScript, expose previews incorrectly, drift from REST schemas, miss redirects, and invalidate caches unreliably.

## Cost

- runtime frontend failures
- draft or private content leakage
- broken SEO and redirects
- stale content
- high migration and framework-switching cost

## Answer

Provide a WordPress plugin, schema registry, contract diff engine, TypeScript code generation, preview/invalidation reference flows, test fixtures and optional adapters.

## Advantage

WordPress remains the source of truth while consumers receive explicit schemas, versioning and compatibility evidence.

## Proof

- contract generated from registered REST schema and metadata
- breaking change detected before merge
- preview permissions tested for authorized and unauthorized users
- redirect and cache invalidation journeys
- sample Next.js and framework-neutral clients

## Ask

Pilot one content type, contribute an adapter, or review the schema/versioning rules against a real project.

## Product principles

1. **Bound every claim.** State exactly which versions, environments, contracts, roles, journeys and evidence support a claim.
2. **Prefer official platform APIs.** Private internals may be studied but must not become undocumented production dependencies.
3. **Prove failure detection.** Every important gate needs a known-bad fixture or mutation proving that it can fail.
4. **Local equals CI.** CI invokes versioned repository commands; it does not contain hidden logic unavailable to contributors.
5. **Safe by default.** Destructive, privileged, remote, secret-bearing or production-targeting behavior requires explicit opt-in.
6. **Documentation is a product surface.** A new contributor must be able to install, reproduce, test and understand limitations without private guidance.
7. **Maintenance is designed before launch.** Compatibility policy, ownership, deprecation, security disclosure and archive criteria exist before v1.0.

## Success outcomes

- A qualified developer can reach the documented demo from a clean clone without guessing.
- A reviewer can map every user-facing promise to code, tests and evidence.
- A maintainer can identify the supported versions, release process and breaking-change policy.
- A security reviewer can find permissions, sensitive data, network access and unsafe operations in one threat model.
- An outside contributor can select a scoped issue, run checks locally and submit a compliant pull request.

## Failure conditions

The project is not ready when it depends on undocumented local services, hides secrets in examples, uses vague compatibility language, lacks negative tests, has unowned critical code, cannot produce release artifacts from a tag, or has no plan for security reports and breaking changes.
