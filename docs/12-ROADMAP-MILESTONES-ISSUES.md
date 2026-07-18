# Roadmap, Milestones and Issue Decomposition

## Release roadmap

| Milestone | Outcome | Exit |
|---|---|---|
| v0.1 | registry and JSON Schema export | All milestone acceptance and audit gates |
| v0.2 | TS generation and diff | All milestone acceptance and audit gates |
| v0.3 | secure preview/invalidation | All milestone acceptance and audit gates |
| v0.4 | redirects/media/locales | All milestone acceptance and audit gates |
| v1.0 | stable manifest and deprecation policy | All milestone acceptance and audit gates |

## Milestone issue groups

Every milestone contains these issue groups:

1. contract/schema and ADR changes;
2. core implementation;
3. platform/adapter integration;
4. known-good fixtures;
5. known-bad/failure fixtures;
6. unit/contract/integration/E2E tests;
7. security/privacy review;
8. accessibility/performance work where relevant;
9. docs/tutorial/troubleshooting;
10. compatibility, upgrade and release evidence.

## Initial issue backlog

- `bootstrap:` initialize repository governance, license, security, support and CODEOWNERS.
- `architecture:` accept ADR-0001 through ADR-0006.
- `schema:` define v0 plan/config/result or pattern/manifest contracts.
- `vertical-slice:` implement smallest end-to-end known-good workflow.
- `failure-proof:` add first intentional regression and verify correct failure.
- `doctor:` report prerequisites, versions and common setup problems.
- `ci:` make local commands authoritative and run target compatibility cell.
- `security:` complete threat model and first permission/redaction/network tests.
- `docs:` publish ten-minute tutorial and troubleshooting.
- `release:` build/install v0.1 artifact from protected tag.

## Prioritization rule

Order work by user value multiplied by risk reduction and learning, divided by implementation plus maintenance cost. Do not prioritize social-media appeal above a stable contract, safe default, failure detection or contributor usability.
