# Strict Audit, Fix Loop and Definition of Done

A release is blocked until every applicable item below is checked with a link to evidence. `N/A` requires written rationale and reviewer approval. “Works on my machine,” screenshots without steps, or an unreviewed generated report are not evidence.

## Product and scope

- [ ] PCAAP problem is validated
- [ ] v1 requirements have IDs and acceptance
- [ ] non-goals and limitations are visible
- [ ] claims match tested scope
## Architecture

- [ ] required ADRs accepted
- [ ] dependency direction enforced
- [ ] public interfaces versioned
- [ ] errors and cancellation defined
- [ ] migrations/recovery designed
## Implementation

- [ ] clean clone succeeds
- [ ] walking skeleton works
- [ ] all required features implemented
- [ ] known-bad fixtures fail
- [ ] cleanup is deterministic
## Security and privacy

- [ ] threat model reviewed
- [ ] authorization negative tests pass
- [ ] secrets/artifacts redacted
- [ ] network/filesystem/command boundaries controlled
- [ ] no unresolved critical/high
## Quality

- [ ] static/unit/contract/integration required checks pass
- [ ] E2E critical journeys pass
- [ ] accessibility policy evidence complete
- [ ] performance/package budgets pass
- [ ] no unexplained flaky quarantine
## Compatibility

- [ ] matrix dated and tied to SHA
- [ ] unsupported versions fail clearly
- [ ] next beta/RC results triaged
- [ ] upgrade and recovery tested
- [ ] artifact installs in clean environment
## Documentation

- [ ] README and tutorial verified by fresh reviewer
- [ ] API/schema docs current
- [ ] troubleshooting includes observed failures
- [ ] compatibility/limitations visible
- [ ] release notes/migration complete
## Open source and release

- [ ] license/dependencies compatible
- [ ] contribution/security/support files complete
- [ ] protected release workflow passes
- [ ] artifact contents/checksums verified
- [ ] maintenance owner and next review assigned

## Audit-fix loop

1. Freeze the candidate commit and record environment.
2. Run all local and CI gates from clean state.
3. Compare implemented files/interfaces against requirements and architecture.
4. Execute known-good and known-bad fixtures.
5. Inspect logs, warnings, deprecations, browser console, PHP errors and artifacts.
6. Record every deviation with severity, owner and reproduction.
7. Fix root cause; add regression test; update docs/ADR if behavior changes.
8. Re-run affected suite and full release gate.
9. Install published candidate artifact and repeat critical journey.
10. Sign evidence index and release only from the verified SHA.

## Completion statement template

> Version X is complete for the tested scope and matrix documented in [evidence link]. It does not claim support outside those cells or replace project-specific security, accessibility, performance or acceptance testing. Known limitations: […].

## Prohibited completion language

Never say “fully secure,” “bug-free,” “works everywhere,” “all updates are safe,” “WCAG certified,” “enterprise certified,” or “nothing can be missing.” State bounded evidence and remaining limitations honestly.
