# WP Content Contracts

> **Document status:** implementation-complete engineering blueprint, not a claim that the software has already been built.

Versioned, testable contracts between WordPress content and external consumers without moving authorization or canonical content rules out of WordPress.

## Who this is for

- headless WordPress teams
- frontend platform engineers
- agencies
- content migration and integration teams

## Start-to-finish route

1. Read `docs/00-PRODUCT-PCAAP.md` and accept or change the problem boundary.
2. Freeze v1 scope using `docs/01-SCOPE-REQUIREMENTS-ACCEPTANCE.md`.
3. Record architecture decisions from `docs/02-ARCHITECTURE-AND-ADRS.md` before scaffolding.
4. Create the exact repository skeleton in `docs/03-REPOSITORY-STRUCTURE.md`.
5. Apply the stack and compatibility policy in `docs/04-STACK-COMPATIBILITY-DEPENDENCIES.md`.
6. Execute phases in `docs/05-IMPLEMENTATION-PLAN.md`; do not jump to polish before contracts and failure paths.
7. Apply security/privacy controls and threat model from `docs/06-SECURITY-PRIVACY-THREAT-MODEL.md`.
8. Build the test system in `docs/07-TEST-QUALITY-ACCESSIBILITY-PERFORMANCE.md`.
9. Enforce merge/release gates in `docs/08-CI-CD-SUPPLY-CHAIN-RELEASE.md`.
10. Produce user, contributor, API and evidence documentation from `docs/09-DOCUMENTATION-DEMO-EVIDENCE.md`.
11. Establish governance and maintainer expectations from `docs/10-OPEN-SOURCE-GOVERNANCE.md`.
12. Operate support, deprecation and incident handling from `docs/11-MAINTENANCE-SUPPORT-INCIDENTS.md`.
13. Follow `docs/12-ROADMAP-MILESTONES-ISSUES.md` and release only through `docs/13-STRICT-AUDIT-FIX-DEFINITION-OF-DONE.md`.
14. Freeze commands and machine contracts in `docs/15-TECHNICAL-CONTRACTS-COMMANDS-SCHEMAS.md`.
15. Execute the decomposed work in `docs/16-IMPLEMENTATION-BACKLOG.md`.
16. Release and transfer maintainership using `docs/17-RELEASE-MANIFEST-AND-HANDOFF.md`.
17. Use `TEMPLATES/` to initialize repository community and CI files; replace all placeholders before publishing.

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
