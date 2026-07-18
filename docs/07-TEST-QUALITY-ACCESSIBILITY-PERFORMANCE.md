# Test and Quality System

## Project-specific test inventory

- Registry and schema unit tests, including invalid definitions.
- REST integration tests for context, permissions, errors, pagination and embeds.
- Golden code-generation tests and TypeScript compile tests.
- Contract diff fixtures for additive, deprecation, required-field and type changes.
- Preview attacker/authorized/editor expiration and replay tests.
- Invalidation duplicate, reorder, timeout, retry and signature-rotation tests.
- Consumer E2E for publish/update/unpublish/delete/redirect/media changes.

## Test layers

| Layer | Purpose | Merge behavior |
|---|---|---|
| Static | Format, lint, type, schema, dependency and policy errors | Required on every PR |
| Unit | Pure logic, edge values and error taxonomy | Required on every PR |
| Contract | Public interfaces/adapters/reporters conform | Required on every PR |
| Integration | WordPress/WooCommerce/database/filesystem/REST behavior | Required on every PR or target cell |
| E2E | Representative user/admin/CLI/browser journeys | Smoke on PR; fuller sampled matrix scheduled/release |
| Security | Permission, input, network, archive, redaction and secret boundaries | Required according to changed paths plus scheduled full run |
| Accessibility | Automated signal plus manual protocol for relevant UI | Automated on PR; manual before release and material UI changes |
| Performance | Package/assets/journey/server budgets | Stable PR budgets; deeper scheduled/release |
| Upgrade | Previous release fixture to candidate and recovery | Required for release |

## Mandatory negative proof

For each critical requirement, create a known-bad fixture, mutation or test double that would pass if the gate were broken. Retain the evidence that the gate fails for the intended reason. A test suite that has never demonstrated failure is not sufficient proof.

## Flakiness policy

- Retry only known infrastructure-sensitive tests, with original failures visible.
- No more than the documented retry count; repeated flakes block release.
- Quarantine requires owner, issue, reason, expiry date and reduced claim scope.
- Randomized tests print seeds and preserve artifacts.
- Time and network behavior use controllable clocks/fakes where possible.

## Accessibility policy

Documentation, CLIs and UIs must be usable with keyboard, zoom/reflow and meaningful names/errors. Automated accessibility scanners are supporting evidence only. Any project-specific manual matrix is stored with date, environment, tester steps, observation and limitation.

## Performance policy

Set budgets from the product need and stable baseline. Report raw samples and aggregation. Distinguish package size, server timings, lab browser measurements and real-user/field data. Never market lab results as universal production outcomes.

## Evidence retention

Release evidence includes commands, git SHA, environment versions, matrix results, logs, reports, screenshots/traces where useful, artifact checksums and known limitations. Secrets and personal data are redacted before upload.
