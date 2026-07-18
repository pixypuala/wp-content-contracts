# Next 48 Updates — wp-content-contracts

## Why this file exists

This is a sequenced, honest backlog of at least 48 planned updates that keeps the repository genuinely active over time. Each item is a real unit of work (one issue or pull request) that advances capability, testing, security, documentation, or maintenance — not artificial busywork. Items are ordered so that early work unblocks later work, and grouped into six release milestones. Nothing here is claimed as already done: this is the forward plan.

## How to use it

Convert each checkbox into a tracked issue, attach it to the matching milestone, and close it with the pull request that satisfies it. Aim for a steady cadence (for example one to three items per week) so commit history, releases, and changelog entries reflect continuous, verifiable progress. Re-open or add items whenever revalidation, upstream releases, or user reports surface new work.

Total planned updates: **48** across **6** milestones.

## M1 — v0.1 Foundations & scaffolding

- [ ] **#01** Scaffold the plugin and a versioned contract schema format
- [ ] **#02** Define what a content contract covers (fields, types, guarantees)
- [ ] **#03** Set up a dev environment with sample content and consumers
- [ ] **#04** Add PHPCS, static analysis, and pre-commit hooks
- [ ] **#05** Create ADRs: keep authorization and canonical rules in WordPress
- [ ] **#06** Add CI validation of contract definitions
- [ ] **#07** Implement the contract registry and version resolver
- [ ] **#08** Add structured error responses for contract violations

## M2 — v0.2 Core capability

- [ ] **#09** Implement contract validation on REST responses
- [ ] **#10** Add schema versioning with backward-compatibility checks
- [ ] **#11** Build a consumer SDK stub generated from a contract
- [ ] **#12** Add a deprecation-window mechanism for contract fields
- [ ] **#13** Implement a contract-diff tool between versions
- [ ] **#14** Add capability-aware field exposure without moving authz out of WordPress
- [ ] **#15** Add a changelog generator for contract changes
- [ ] **#16** Provide a compatibility shim for one prior contract version

## M3 — v0.3 Testing, evidence & negative proof

- [ ] **#17** Add contract tests that fail on breaking schema drift
- [ ] **#18** Add a known-bad fixture: removed field without deprecation is rejected
- [ ] **#19** Add integration tests for a sample external consumer
- [ ] **#20** Add golden-file tests for contract serialization
- [ ] **#21** Add tests proving authorization stays server-side
- [ ] **#22** Create an evidence index mapping guarantees to tests
- [ ] **#23** Add a coverage gate for the validation layer
- [ ] **#24** Add performance tests for contract validation overhead

## M4 — v0.4 Security, compatibility & performance

- [ ] **#25** Threat-model contract exposure and field leakage
- [ ] **#26** Add validation that no unauthorized field is ever emitted
- [ ] **#27** Ensure no PII leaks through default contracts
- [ ] **#28** Add a WordPress/PHP support matrix and test the floor
- [ ] **#29** Add supply-chain scanning
- [ ] **#30** Add observability for contract-violation rates
- [ ] **#31** Document rollback for a bad contract release
- [ ] **#32** Add signed releases and checksums

## M5 — v0.5 Documentation, DX & adoption

- [ ] **#33** Write a case study on a prevented consumer-breaking change
- [ ] **#34** Record a demo and reproducible Playground blueprint
- [ ] **#35** Publish the contract-authoring and versioning guide
- [ ] **#36** Document the consumer-integration workflow
- [ ] **#37** Add architecture docs for the registry and resolver
- [ ] **#38** Write a migration guide between contract versions
- [ ] **#39** Document the deprecation policy and windows
- [ ] **#40** Add a troubleshooting guide for validation failures

## M6 — v1.0+ Community, release cadence & maintenance

- [ ] **#41** Adopt semantic versioning for contracts and the plugin
- [ ] **#42** Add protected-tag release automation with evidence
- [ ] **#43** Set a cadence to revalidate against new WordPress REST changes
- [ ] **#44** Add a quarterly contract-review to the roadmap
- [ ] **#45** Publish a breaking-change policy
- [ ] **#46** Triage issues with documented labels and SLAs
- [ ] **#47** Add 'good first issue' consumer-example tasks
- [ ] **#48** Schedule recurring dependency and schema reviews

## Honesty note

These updates are planned, not completed. They do not assert the software is already built, adopted, certified, bug-free, or secure in every environment. They describe the intended, testable path of work and the cadence for keeping the repository maintained.
