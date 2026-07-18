# Open-Source Governance and Contribution

## Governance before popularity

Define maintainers, decision rights, release rights, security contacts, ownership transfer and archive process before accepting substantial outside contributions. A single maintainer may start the project but must document what happens during absence.

## Contribution workflow

1. Search issues/discussions and upstream projects.
2. Open an issue for public API, architecture, major dependency or broad feature changes.
3. Maintainer confirms scope, acceptance and owner.
4. Contributor creates a narrow branch and follows repository commands.
5. PR template maps changes to tests, security, docs, compatibility and release note.
6. Required reviewers approve; unresolved substantive feedback is not dismissed by force-push.
7. Merge records attribution and changelog category.

## Issue quality

Bug reports include version/environment, minimal reproduction, expected/actual behavior, logs with secrets removed and whether the bug reproduces from a clean fixture. Feature requests name user/problem and alternatives, not only a proposed implementation.

## Community standards

- Use a recognized code of conduct and publish enforcement contacts/process.
- Be explicit about unpaid maintainer capacity and response expectations.
- Label `good first issue` only when scope, files, acceptance and test path are clear.
- Do not pressure reporters to disclose vulnerabilities publicly.
- Avoid duplicate ecosystem fragmentation: contribute upstream when ownership and scope clearly belong there.
- Credit design, documentation, testing, issue triage and accessibility work—not only code.

## Decision process

Routine fixes use maintainer review. Public API/schema, license, security model, repository split, minimum-version and governance changes require ADR/discussion and a documented decision period. Emergency security releases may be private until coordinated disclosure, followed by a public retrospective that does not expose unnecessary exploit details.

## Sustainability

Track bus factor, issue age, release cadence, compatibility freshness, security-response capacity and maintainer workload. Funding/sponsorship must not buy unsafe roadmap priority or hidden influence; disclose material sponsorship and conflicts.
